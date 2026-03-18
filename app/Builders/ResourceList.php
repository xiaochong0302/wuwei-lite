<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Builders;

use App\Repos\Upload as UploadRepo;

class ResourceList extends Builder
{

    public function handleUploads(array $relations): array
    {
        $uploads = $this->getUploads($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['upload'] = $uploads[$value['upload_id']] ?? null;
        }

        return $relations;
    }

    public function getUploads(array $relations): array
    {
        $ids = kg_array_column($relations, 'upload_id');

        $uploadRepo = new UploadRepo();

        $columns = ['id', 'name', 'path', 'mime', 'md5', 'size'];

        $uploads = $uploadRepo->findByIds($ids, $columns);

        $result = [];

        foreach ($uploads->toArray() as $upload) {

            $id = $this->crypt->encryptBase64($upload['id'], null, true);

            $upload['url'] = $this->url->get(['for' => 'home.download', 'id' => $id]);

            $result[$upload['id']] = $upload;
        }

        return $result;
    }

}
