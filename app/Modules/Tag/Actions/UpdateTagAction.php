<?php

namespace App\Modules\Tag\Actions;

use App\Modules\Tag\Tag;

class UpdateTagAction
{
    public function execute(Tag $tag, array $data): Tag
    {
        $tag->update($data);

        return $tag;
    }
}
