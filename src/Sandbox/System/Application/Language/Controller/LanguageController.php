<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Application\Language\Controller;

use Sandbox\Core\Application\Controller\CommandController;
use Sandbox\System\Domain\Language\Command\CreateLanguageCommand;
use Sandbox\System\Domain\Language\Command\DisableLanguageCommand;
use Sandbox\System\Domain\Language\Command\EnableLanguageCommand;
use Sandbox\System\Domain\Language\Command\UpdateLanguageNameCommand;
use Sandbox\System\Domain\Language\LanguageId;

/**
 * LanguageController class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class LanguageController extends CommandController
{
    /**
     * @param string $code
     * @param array  $name
     * @param string $defaultLocale
     *
     * @return string
     */
    public function createAction($code, array $name, $defaultLocale)
    {
        $languageId = LanguageId::next()->toNative();
        $this->commandBus()->dispatch(
            new CreateLanguageCommand($languageId, $code, $name, $defaultLocale)
        );

        return $languageId;
    }

    /**
     * @param string $languageId
     * @param array  $name
     *
     * @return string
     */
    public function updateNameAction($languageId, array $name)
    {
        $this->commandBus()->dispatch(
            new UpdateLanguageNameCommand($languageId, $name)
        );

        return true;
    }

    /**
     * @param string $languageId
     *
     * @return bool
     */
    public function disableAction($languageId)
    {
        $this->commandBus()->dispatch(
            new DisableLanguageCommand($languageId)
        );

        return true;
    }

    /**
     * @param string $languageId
     *
     * @return bool
     */
    public function enableAction($languageId)
    {
        $this->commandBus()->dispatch(
            new EnableLanguageCommand($languageId)
        );

        return true;
    }
}
