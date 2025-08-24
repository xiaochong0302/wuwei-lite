<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Builders;

use App\Repos\Chapter as ChapterRepo;

class CommentList extends Builder
{

    public function handleChapters(array $comments): array
    {
        $chapters = $this->getChapters($comments);

        foreach ($comments as $key => $comment) {
            $comments[$key]['chapter'] = $chapters[$comment['chapter_id']] ?? null;
        }

        return $comments;
    }

    public function handleUsers(array $comments): array
    {
        $users = $this->getUsers($comments);

        foreach ($comments as $key => $comment) {
            $comments[$key]['owner'] = $users[$comment['owner_id']] ?? null;
            $comments[$key]['to_user'] = $users[$comment['to_user_id']] ?? null;
        }

        return $comments;
    }

    public function getChapters(array $reviews): array
    {
        $ids = kg_array_column($reviews, 'chapter_id');

        $chapterRepo = new ChapterRepo();

        $chapters = $chapterRepo->findByIds($ids, ['id', 'title']);

        $result = [];

        foreach ($chapters->toArray() as $chapter) {
            $result[$chapter['id']] = $chapter;
        }

        return $result;
    }

    public function getUsers(array $comments): array
    {
        $ownerIds = kg_array_column($comments, 'owner_id');
        $toUserIds = kg_array_column($comments, 'to_user_id');
        $ids = array_merge($ownerIds, $toUserIds);

        return $this->getShallowUserByIds($ids);
    }

}
