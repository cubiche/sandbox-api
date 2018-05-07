#Validators

Validators are inside `Validation` folder. They are in charge of validate custom rules for our Domain.

## How to create validators

Inside every `Validation` we have `Exeptions` and `Rules`.
* Rules: Are used to create the logic of the validation. They will have a method validate that will receive the input to validate. Return true if its valid.
* Exeptions: Will be throw when a rule will not pass. Will have the same name as the rule with the suffix `Exception`.

Also inside the `Validation` folder will have all the Validator classes.
This classes will have a method with the suffix `Validator` that will receive the command with the same name and the class metadata.
This will be used to add the new validation rules to the command.

## How to test validators

### Testing validators

For testing validators we need to create commands that will not pass the rules and check if the exception is thrown.

### Testing rules

We need to check all the rules will return true when valid and false when it's invalid.

### Testing exceptions

This can be omit because the generator of test classes will create a correct test for you.

## How to register validators

We need to register our validators to make use of them this is done doing the following things:

### Register Rules
* In `src/AppBundle/AppBundle.php` we need to add a line to register our folder of rules. Example:
```php
    Validator::registerValidator('Sandbox\\System\\Domain\\Language\\Validation\\Rules', true);
```

### Register Validators
* In `app/config/services` we need to define our validator in its correspondent bounded context inside `services.xml`. Example:
```xml
    <service id="app.environment.command_validator" class="Sandbox\Environment\Domain\Environment\Validation\EnvironmentValidator">
        <argument type="service" id="cubiche.query_bus"/>
        <!--We need one tag for every command we want to track for this validator-->
        <tag name="cubiche.command.validator" class="Sandbox\Environment\Domain\Environment\Command\CreateEnvironmentCommand" />
    </service>
```

### Register Rules and Validators for the tests
* We also need to define it for our tests. Inside our bounded context test folder in `TestCase.php`.
We need to register our new rule and also our validator for the usage inside tests. Example:
```php
    //Rule
    protected function registerValidatorNamespace()
    {
        Validator::registerValidator('Sandbox\\Environment\\Domain\\Environment\\Validation\\Rules', true);
    }
    
    //Validator
    protected function commandValidatorHandlers()
    {
        $environmentValidator = new EnvironmentValidator($this->queryBus());

        return array(
            CreateEnvironmentCommand::class => $environmentValidator,
        );
    }
```
