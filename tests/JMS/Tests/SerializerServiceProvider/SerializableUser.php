<?php

/*
 * This file is part of the Silex framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace JMS\Tests\SerializerServiceProvider;

use DateTime;
use JMS\SerializerBundle\Annotation\Type;

/**
 * This class is used by the SerializerServiceProvider.
 *
 * @author Marijn Huizendveld <marijn@pink-tie.com>
 */
class SerializableUser
{
    /**
     * @JMS\SerializerBundle\Annotation\Type("integer")
     */
    private $id;

    /**
     * @JMS\SerializerBundle\Annotation\Type("string")
     */
    private $name;

    /**
     * @JMS\SerializerBundle\Annotation\Type("DateTime")
     */
    private $created;

    public function __construct($id, $name, DateTime $created)
    {
        $this->id = $id;
        $this->name = $name;
        $this->created = $created;
    }
}
