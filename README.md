Invoke: Route Authentication/Authorization Management
===========

[![Travis-CI Build Status](https://secure.travis-ci.org/psecio/invoke.png?branch=master)](http://travis-ci.org/psecio/invoke)
[![Total Downloads](https://img.shields.io/packagist/dt/psecio/invoke.svg?style=flat-square)](https://packagist.org/packages/psecio/invoke)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/psecio/invoke/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/psecio/invoke/?branch=master)

The Invoke system helps you protect your application based on the endpoints and the URI requested. It uses a configuration file (or array of settings) to define the permissions needed to request a resource. For example, it will let you define things like:

"For this endpoint, I want to allow only authenticated users that have the group named 'test' to get through".

Currently Invoke treats all criteria as **AND**s so they must meet **ALL** criteria in order to pass the validation.

## Example Usage

```php
<?php
$en = new \Psecio\Invoke\Enforcer(__DIR__.'/config/routes.yml');

$allowed = $en->isAuthorized(
    new InvokeUser(array('username' => 'ccornutt')),
    new \Psecio\Invoke\Resource()
);

if ($allowed === true) {
	echo 'Good to go!';
}
?>
```

In this case we're passing in an instance of the `InvokeUser` class that implements the `\Psecio\Invoke\UserInterface` for consistent user handling. This class defines three methods:

- `getGroups` for returning a set of instances of the `InvokeGroup` objects
- `getPermissions`
- `isAuthed` to determine if the user is authenticated

Each of these should be implemented in your own class to return these same values. This is a "bridge" between whatever user system you're using and the Invoke checking.

The `InvokeGroup` class should implement the `\Psecio\Invoke\GroupInterface` and should have the methods:

- `getName` to return a string name for the group
- `getPermissions`

The Invoke tool assumes a typical RBAC group/permissions setup, but it can be used to determine permissions directly on the user. As such there is also a permission interface in `\Psecio\Invoke\PermissionInterface` with a single method:

- `getName` to return the "name" of the current permission


**Optionally** you can just have the `getPermissions` and `getGroups` methods on the `InvokeUser` object retrurn an array of strings instead of sets of `InvokePermission` and `InvokeGroup` respectively. This greatly simplifies the process and requires less overhead for you to implement. For example, instead of making the permission class and returning instances:

```php
<?php
class MyGroup implements \Psecio\Invoke\GroupInterface { }
class MyUser implements \Psecio\Invoke\UserInterface
{
  public function getGroups()
  {
    return [ new MyGroup(), new MyGroup() ];
  }
}
?>
```

## Configuration

The configuration is based on a YAML formatted file. Here's an example structure:

```yaml
event/add:
  protected: on
  groups: [test]
  permissions: [testperm1]
```

In this example we're telling the system that the `/event/add` route should be protected (only allow authenticated users) and that it requires that the user has the group named "test" and a permission on the user of "testperm1". The system will take in this configuration and automatically parse and handle is accordingly inside the `Enforcer`.

Routes can be simple matches or they can be more complicated regular expressions. For example, if we only wanted to match URLs going to our `/event/view` page with numeric IDs, you could use:

```yaml
event/view/([0-9]+):
  protected: on
  groups: [test]
  permissions: [testperm1]
  methods: [get, post]
```

This would match a URL like `/event/view/1` but not `/event/view/foo`. The route itself is actually a regular expression. If you're familiar with regular expressions, you'll also notice that there's capturing parentheses in our example. These can be used to gather the matching data from our matcher instance:

```php
<?php
$config = array('/event/view/([0-9]+)');
$uri = '/event/view/1234';

$matcher = new \Psecio\Invoke\Match\Route\Regex($config);
if ($matcher->evaluate($uri) === true) {
	$params = $matcher->getParmas();
}
?>
```

This would return the following in `$params`:

```
Array (
	[0] => /event/view/1234
	[1] => 1234
)
```

## Match Types

There are currently several match types in the Invoke system that can be used for evaluation: route matching, group checking and permission checking. You don't need to do anything externally to use these matches - they're generated from the configuration file for you.

- `Match/User/HasGroup`
- `Match/User/HasPermission`
- `Match/Route/Regex`
- 'Match/Resource/HasMethod'

There's more of these match types to come...so stay tuned.

