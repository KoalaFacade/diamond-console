<?php

namespace Tests\Unit\DataTransferObjects\Fixtures;

use KoalaFacade\DiamondConsole\Foundation\DataTransferObject;

readonly class UserData extends DataTransferObject
{
    public function __construct(
        public ?string $name = null,
        /** @var array<int, RoleData> | null $roles */
        public ?array $roles = null,
        public ?RoleData $mainRole = null,
        public ?int $age = null,
        public ?GenderEnum $gender = null,
        /** @var array<string, string> | null $address */
        public ?array $addresses = null
    ) {
    }
}
