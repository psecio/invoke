Invoke: Route Authentication/Authorization Management
===========

[![Travis-CI Build Status](https://secure.travis-ci.org/psecio/invoke.png?branch=master)](http://travis-ci.org/psecio/invoke)
[![Total Downloads](https://img.shields.io/packagist/dt/psecio/invoke.svg?style=flat-square)](https://packagist.org/packages/psecio/invoke)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/psecio/invoke/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/psecio/invoke/?branch=master)

## Introduction video

[![Route Protection with Invoke: Introduction](http://img.youtube.com/vi/FvQpwz-l-Yg/0.jpg)](https://youtu.be/FvQpwz-l-Yg)

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

Additionally, the routes also support the idea of *placeholders* and *parameters* to do additional checking. To use these placeholders, you use a colon notation in the path and then reference them in a `params` check in the body. For example, say you wanted to only match an event with an ID of 5:

```yaml
event/view/:id:
  protected: on
  params: [id:5]
```

## Inheritance
Invoke also includes the concept of inheritance, allowing for the ultimate reuse of evaluation rules. This allows you to set up one route how you'd like it and then just tell other routes to inherit it.


> **NOTE:** This inheritance *adds* the checks from the other route, not *replaces*.

This uses the `inherit` and `name` keywords to match the routes togethter. If you don't give a route a name, the library cannot match for inheritance:

```yaml
event/admin:
    protected: on
    groups: [group1]
    name: event-add
event/add:
    inherit: event-add
```

So, in this example we're telling Invoke that when the user accesses the `event/add` endpoint we want all the checks from `event/admin` to be added to it. In this case it's just that the endpoint is protected and that they're in the group "group1".


So, if the user comes to `/event/view/5` (and was logged in), this route would match and the `isAuthorized` call would return `true`.

## Match Types

There are currently several match types in the Invoke system that can be used for evaluation: route matching, group checking and permission checking. You don't need to do anything externally to use these matches - they're generated from the configuration file for you.

- `Match/User/HasGroup`
- `Match/User/HasPermission`
- `Match/Route/Regex`
- `Match/Route/HasParameters`
- `Match/Resource/HasMethod`
- `Match/Resource/IsProtected`
- `Match/Object/Callback`

There's more of these match types to come...so stay tuned.


## Callback Match

The `callback` match type allows you to call your own class and method directly and evaluate the result of the check. The method should return a `boolean` value. The method should be defined as static in order to be called correctly. For example:

```yaml
event/view/:id:
  protected: on
  callback: \App\MyUser::checkAccess
```

Then, in your class:

```php
<?php
namespace App;

class MyUser
{
  public static checkAccess($data)
  {
    $result = false;
    /* return the result of the evaluation */
    return $result;
  }
}
?>
```

The callback should take one parameter, the `$data` value that's an instance of `\Psecio\Invoke\Data`. This object allows you access to:

- the current user (`\Psecio\Invoke\InvokeUser`)
- resource requested (`\Psecio\Invoke\Resource`)
- the route that matches (`\Psecio\Invoke\RouteContainer`)

These three things provide the context you'll need to evaluate the request. This information can be accessed through the `$data->user`, `$data->resource` and `$data->route` properties respectively.

## Failure

if the result of the `isAuthorized` call is `false`, you can query the object to get the error message from the first match that failed:

```php
<?php
$en = new \Psecio\Invoke\Enforcer(__DIR__.'/config/routes.yml');

$allowed = $en->isAuthorized(
    new InvokeUser(array('username' => 'ccornutt')),
    new \Psecio\Invoke\Resource()
);

if ($allowed === false) {
  echo 'ERROR: '.$en->getError();
}
?>
```
