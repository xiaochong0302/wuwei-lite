<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services;

use App\Builders\CommentList as CommentListBuilder;
use App\Http\Admin\Services\Traits\AccountSearchTrait;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Comment as CommentModel;
use App\Repos\Comment as CommentRepo;
use App\Services\Logic\Comment\CountTrait;
use App\Validators\Comment as CommentValidator;
use Phalcon\Paginator\RepositoryInterface;

class Comment extends Service
{

    use CountTrait;
    use AccountSearchTrait;

    public function getPublishTypes(): array
    {
        return CommentModel::publishTypes();
    }

    public function getComments(): RepositoryInterface
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $commentRepo = new CommentRepo();

        $pager = $commentRepo->paginate($params, $sort, $page, $limit);

        return $this->handleComments($pager);
    }

    public function getComment(int $id): CommentModel
    {
        return $this->findOrFail($id);
    }

    public function updateComment(int $id): CommentModel
    {
        $comment = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new CommentValidator();

        $data = [];

        if (isset($post['content'])) {
            $data['content'] = $validator->checkContent($post['content']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        $comment->assign($data);

        $comment->update();

        $this->eventsManager->fire('Comment:afterUpdate', $this, $comment);

        return $comment;
    }

    public function deleteComment(int $id): CommentModel
    {
        $comment = $this->findOrFail($id);

        $comment->deleted = 1;

        $comment->update();

        $validator = new CommentValidator();

        if ($comment->parent_id > 0) {
            $parent = $validator->checkParent($comment->parent_id);
            $this->recountCommentReplies($parent);
        }

        $chapter = $validator->checkChapter($comment->chapter_id);

        $this->recountChapterComments($chapter);

        $this->eventsManager->fire('Comment:afterDelete', $this, $comment);

        return $comment;
    }

    public function restoreComment(int $id): CommentModel
    {
        $comment = $this->findOrFail($id);

        $comment->deleted = 0;

        $comment->update();

        $validator = new CommentValidator();

        if ($comment->parent_id > 0) {
            $parent = $validator->checkParent($comment->parent_id);
            $this->recountCommentReplies($parent);
        }

        $chapter = $validator->checkChapter($comment->chapter_id);

        $this->recountChapterComments($chapter);

        $this->eventsManager->fire('Comment:afterRestore', $this, $comment);

        return $comment;
    }

    public function batchModerate(): void
    {
        $type = $this->request->getQuery('type', ['trim', 'string']);
        $ids = $this->request->getPost('ids', ['trim', 'int']);

        $commentRepo = new CommentRepo();

        $comments = $commentRepo->findByIds($ids);

        if ($comments->count() == 0) return;

        $validator = new CommentValidator();

        foreach ($comments as $comment) {

            if ($type == 'approve') {

                $chapter = $validator->checkChapter($comment->chapter_id);

                $this->recountChapterComments($chapter);

                $comment->published = CommentModel::PUBLISH_APPROVED;

                $comment->update();

                if ($comment->parent_id > 0) {
                    $parent = $validator->checkParent($comment->parent_id);
                    $this->recountCommentReplies($parent);
                }

            } elseif ($type == 'reject') {

                $comment->published = CommentModel::PUBLISH_REJECTED;

                $comment->update();
            }
        }
    }

    public function batchDelete(): void
    {
        $ids = $this->request->getPost('ids', ['trim', 'int']);

        $commentRepo = new CommentRepo();

        $comments = $commentRepo->findByIds($ids);

        if ($comments->count() == 0) return;

        $validator = new CommentValidator();

        foreach ($comments as $comment) {

            $comment->deleted = 1;

            $comment->update();

            if ($comment->parent_id > 0) {
                $parent = $validator->checkParent($comment->parent_id);
                $this->recountCommentReplies($parent);
            }

            $chapter = $validator->checkChapter($comment->chapter_id);

            $this->recountChapterComments($chapter);
        }
    }

    protected function findOrFail(int $id): CommentModel
    {
        $validator = new CommentValidator();

        return $validator->checkComment($id);
    }

    protected function handleComments(RepositoryInterface $pager): RepositoryInterface
    {
        if ($pager->getTotalItems() > 0) {

            $builder = new CommentListBuilder();

            $items = $pager->getItems()->toArray();

            $pipeA = $builder->handleUsers($items);
            $pipeB = $builder->handleChapters($pipeA);
            $pipeC = $builder->objects($pipeB);

            $pager->setItems($pipeC);
        }

        return $pager;
    }

}
