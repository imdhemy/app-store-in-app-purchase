# Upgrade from 1.0 to 2.0

## Client Factory

- The `$sandbox` parameter in the `ClientFactory::create()` method is removed, use the `ClientFactory::createSandbox()`
  method instead.

## Value objects

- Use `\Imdhemy\AppStore\ValueObjects\Time::toCarbon` instead of `\Imdhemy\AppStore\ValueObjects\Time::getCarbon`  
