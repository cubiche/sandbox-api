<?php

/**
 * This file is part of the Sandbox application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sandbox\Core\Infrastructure\GraphQL;

use Cubiche\Core\Validator\Exception\ValidationException;
use Sandbox\Conference\Infrastructure\GraphQL\Mutation\CreateConference;
use Sandbox\Conference\Infrastructure\GraphQL\Query\FindAllConferences;
use Sandbox\Conference\Infrastructure\GraphQL\Query\FindOneConferenceById;
use Sandbox\Reservation\Infrastructure\Order\GraphQL\Mutation\CreateOrder;
use Sandbox\Reservation\Infrastructure\Order\GraphQL\Mutation\ExpireOrder;
use Sandbox\Reservation\Infrastructure\Order\GraphQL\Mutation\PayOrder;
use Sandbox\Reservation\Infrastructure\Order\GraphQL\Query\FindAllOrders;
use Sandbox\Reservation\Infrastructure\Order\GraphQL\Query\FindAllOrdersByCurrentUser;
use Sandbox\Reservation\Infrastructure\Order\GraphQL\Query\FindAllOrdersByUserId;
use Sandbox\Reservation\Infrastructure\Order\GraphQL\Query\FindOneOrderById;
use Sandbox\Reservation\Infrastructure\SeatsAvailability\GraphQL\Query\FindOneSeatsAvailabilityById;
use Sandbox\Security\Infrastructure\Permission\GraphQL\Query\FindAllPermissions;
use Sandbox\Security\Infrastructure\Role\GraphQL\Mutation\AddPermissionsToRole;
use Sandbox\Security\Infrastructure\Role\GraphQL\Mutation\CreateRole;
use Sandbox\Security\Infrastructure\Role\GraphQL\Mutation\RemovePermissionsFromRole;
use Sandbox\Security\Infrastructure\Role\GraphQL\Query\FindAllRoles;
use Sandbox\Security\Infrastructure\Role\GraphQL\Query\FindOneRoleById;
use Sandbox\Security\Infrastructure\Role\GraphQL\Query\FindOneRoleByName;
use Sandbox\Security\Infrastructure\User\GraphQL\Mutation\CreateUser;
use Sandbox\Security\Infrastructure\User\GraphQL\Mutation\DisableUser;
use Sandbox\Security\Infrastructure\User\GraphQL\Mutation\EnableUser;
use Sandbox\Security\Infrastructure\User\GraphQL\Mutation\LoginUser;
use Sandbox\Security\Infrastructure\User\GraphQL\Mutation\LogoutUser;
use Sandbox\Security\Infrastructure\User\GraphQL\Mutation\ResetUserPasswordRequest;
use Sandbox\Security\Infrastructure\User\GraphQL\Mutation\VerifyUser;
use Sandbox\Security\Infrastructure\User\GraphQL\Query\FindAllUsers;
use Sandbox\Security\Infrastructure\User\GraphQL\Query\FindAuthenticatedUser;
use Sandbox\Security\Infrastructure\User\GraphQL\Query\FindOneUserByPasswordResetToken;
use Sandbox\System\Infrastructure\Country\GraphQL\Query\FindAllCountries;
use Sandbox\System\Infrastructure\Currency\GraphQL\Query\FindAllCurrencies;
use Sandbox\System\Infrastructure\Currency\GraphQL\Query\FindOneCurrency;
use Sandbox\System\Infrastructure\Language\GraphQL\Query\FindAllLanguages;
use Sandbox\System\Infrastructure\Language\GraphQL\Query\FindOneLanguage;
use Youshido\GraphQL\Config\Schema\SchemaConfig;
use Youshido\GraphQL\Exception\Interfaces\LocationableExceptionInterface;
use Youshido\GraphQL\Schema\AbstractSchema;

/**
 * ApplicationSchema class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class ApplicationSchema extends AbstractSchema
{
    /**
     * {@inheritdoc}
     */
    public function build(SchemaConfig $config)
    {
        $config->getQuery()->addFields([
            new FindAllLanguages(),
            new FindOneLanguage(),
            new FindAllCurrencies(),
            new FindOneCurrency(),
            new FindAllRoles(),
            new FindOneRoleById(),
            new FindOneRoleByName(),
            new FindAllPermissions(),
            new FindAllCountries(),
            new FindAllUsers(),
            new FindAuthenticatedUser(),
            new FindOneUserByPasswordResetToken(),
            new FindAllConferences(),
            new FindOneConferenceById(),
            new FindAllOrders(),
            new FindAllOrdersByUserId(),
            new FindAllOrdersByCurrentUser(),
            new FindOneOrderById(),
            new FindOneSeatsAvailabilityById(),
        ]);

        $config->getMutation()->addFields([
            new CreateUser(),
            new VerifyUser(),
            new DisableUser(),
            new EnableUser(),
            new LoginUser(),
            new LogoutUser(),
            new ResetUserPasswordRequest(),
            new CreateRole(),
            new AddPermissionsToRole(),
            new RemovePermissionsFromRole(),
            new CreateConference(),
            new CreateOrder(),
            new ExpireOrder(),
            new PayOrder(),
        ]);
    }

    /**
     * @param array $errors
     *
     * @return array
     */
    public static function formatErrors(array $errors)
    {
        $result = [];
        foreach ($errors as $error) {
            if ($error instanceof ValidationException) {
                $validationErrors = array(
                    'message' => $error->getMessage(),
                    'code' => $error->getCode(),
                    'errors' => array(),
                );

                foreach ($error->getErrorExceptions() as $errorException) {
                    $validationErrors['errors'][] = array(
                        'message' => $errorException->getMessage(),
                        'code' => $errorException->getCode(),
                        'path' => $errorException->getPropertyPath(),
                    );
                }

                $result[] = $validationErrors;
            } elseif ($error instanceof LocationableExceptionInterface) {
                $result[] = array_merge(
                    ['message' => $error->getMessage()],
                    $error->getLocation() ? ['locations' => [$error->getLocation()->toArray()]] : [],
                    $error->getCode() ? ['code' => $error->getCode()] : []
                );
            } else {
                $result[] = array_merge(
                    ['message' => $error->getMessage()],
                    $error->getCode() ? ['code' => $error->getCode()] : []
                );
            }
        }

        return $result;
    }
}
