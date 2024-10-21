create database pokemons
    default character set utf8
    collate utf8_unicode_ci;

use pokemons;

create user pokemonuser@localhost
    identified by 'FERNANDO';

grant all
    on pokemons.*
    to pokemonuser@localhost;

flush privileges;