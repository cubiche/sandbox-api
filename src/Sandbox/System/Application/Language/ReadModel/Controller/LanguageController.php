<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Application\Language\ReadModel\Controller;

use Cubiche\Core\Collections\CollectionInterface;
use Sandbox\Core\Application\Controller\QueryController;
use Sandbox\System\Domain\Language\ReadModel\Language;
use Sandbox\System\Domain\Language\ReadModel\Query\FindAllLanguages;
use Sandbox\System\Domain\Language\ReadModel\Query\FindOneLanguageByCode;

/**
 * LanguageController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class LanguageController extends QueryController
{
    /**
     * @return CollectionInterface|Language[]
     */
    public function findAllAction()
    {
        return $this->queryBus()->dispatch(new FindAllLanguages());
    }

    /**
     * @param string $code
     *
     * @return Language|null
     */
    public function findOneByCodeAction($code)
    {
        return $this->queryBus()->dispatch(
            new FindOneLanguageByCode($code)
        );
    }
}
