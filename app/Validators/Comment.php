<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Chapter as ChapterModel;
use App\Models\Comment as CommentModel;
use App\Repos\Comment as CommentRepo;

class Comment extends Validator
{

    public function checkComment(int $id): CommentModel
    {
        $commentRepo = new CommentRepo();

        $comment = $commentRepo->findById($id);

        if (!$comment) {
            throw new BadRequestException('comment.not_found');
        }

        return $comment;
    }

    public function checkParent(int $id): CommentModel
    {
        $commentRepo = new CommentRepo();

        $comment = $commentRepo->findById($id);

        if (!$comment) {
            throw new BadRequestException('comment.parent_not_found');
        }

        return $comment;
    }

    public function checkChapter(int $chapterId): ChapterModel
    {
        $validator = new Chapter();

        return $validator->checkChapter($chapterId);
    }

    public function checkContent(string $content): string
    {
        $value = $this->filter->sanitize($content, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('comment.content_too_short');
        }

        if ($length > 255) {
            throw new BadRequestException('comment.content_too_long');
        }

        return $value;
    }

    public function checkPublishStatus(int $status): int
    {
        if (!array_key_exists($status, CommentModel::publishTypes())) {
            throw new BadRequestException('comment.invalid_publish_status');
        }

        return $status;
    }

}
