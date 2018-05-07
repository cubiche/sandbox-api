<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\System\Domain\Language;

use Sandbox\Core\Domain\Exception\NotFoundException;
use Sandbox\System\Domain\Language\Command\CreateLanguageCommand;
use Sandbox\System\Domain\Language\Command\DisableLanguageCommand;
use Sandbox\System\Domain\Language\Command\EnableLanguageCommand;
use Sandbox\System\Domain\Language\Command\UpdateLanguageNameCommand;
use Cubiche\Domain\Locale\LanguageCode;
use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\Repository\RepositoryInterface;

/**
 * LanguageCommandHandler class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class LanguageCommandHandler
{
    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * LanguageCommandHandler constructor.
     *
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CreateLanguageCommand $command
     */
    public function createLanguage(CreateLanguageCommand $command)
    {
        $language = new Language(
            LanguageId::fromNative($command->languageId()),
            LocalizableString::fromArray($command->name(), $command->defaultLocale()),
            LanguageCode::fromNative($command->code())
        );

        $this->repository->persist($language);
    }

    /**
     * @param UpdateLanguageNameCommand $command
     */
    public function updateLanguageName(UpdateLanguageNameCommand $command)
    {
        $language = $this->findOr404($command->languageId());
        $language->updateName(LocalizableString::fromArray($command->name()));

        $this->repository->persist($language);
    }

    /**
     * @param EnableLanguageCommand $command
     */
    public function enableLanguage(EnableLanguageCommand $command)
    {
        $language = $this->findOr404($command->languageId());
        $language->enable();

        $this->repository->persist($language);
    }

    /**
     * @param DisableLanguageCommand $command
     */
    public function disableLanguage(DisableLanguageCommand $command)
    {
        $language = $this->findOr404($command->languageId());
        $language->disable();

        $this->repository->persist($language);
    }

    /**
     * @param string $languageId
     *
     * @return Language
     */
    private function findOr404($languageId)
    {
        /** @var Language $language */
        $language = $this->repository->get(LanguageId::fromNative($languageId));
        if ($language === null) {
            throw new NotFoundException(sprintf(
                'There is no language with id: %s',
                $languageId
            ));
        }

        return $language;
    }
}
