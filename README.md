# Sales Rabbit Code Challenge

## Requirements

* [Composer](https://getcomposer.org/)

* [Vagrant](https://www.vagrantup.com/)

## Setup

* Clone the project and in a terminal run
```
$ composer install
```

* Setup Homestead
```
php vendor/bin/homestead make
```

* Start up the vagrant box
`vagrant up`

* SSH into vagrant
`vagrant ssh`

* From within the vagrant box change directory and run the migration and seeder 
`cd ~/code`
`php artisan migrate:refresh --seed`

## The Challenge

This projects inlcudes a migration and seeder that will create a nested set hierarchy. Your goal is to create Restful endpoints to Create, Read, Update and Delete nested set nodes.

## Expectations

Eloquent/Query Builder only. Do not use raw queries.

The Update endpoint must include the ability to move a node to a new parent. If the node has child nodes, it must to also move the child nodes and adjust the Left `lft`, Right `rgt` and Level `level` accordingly.

There shouldn't be gaps in Left or Right, and Level must be accuraty represented.
