<?php

namespace Tests\Unit\DataTransferObjects\Fixtures;

use KoalaFacade\DiamondConsole\Foundation\DataTransferObject;

readonly class UserData extends DataTransferObject
{
    public function __construct(
        public string | null $name = null,
        /** @var array<int, RoleData> | null $roles */
        public array | null $roles = null,
        public RoleData | null $mainRole = null,
        public int | null $age = null,
        public GenderEnum | null $gender = null,
        /** @var array<string, string> | null $address */
        public array | null $addresses = null
    ) {
    }
}
