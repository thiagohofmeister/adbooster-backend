<?php

namespace App\Service;

use App\Service\Base\Service\Contract;

/**
 * @todo Document class Test.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Test extends Contract
{
    public function retrieve($id)
    {
        die("retrieve " . $id);
    }

    public function save()
    {
        die("save");
    }
}
