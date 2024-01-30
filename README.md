HalloVerdenRequestEntityBundle
==============================
Deserializes a request to an entity.

Installation
============

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require halloverden/symfony-request-entity-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require halloverden/request-entity-bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    HalloVerden\RequestEntityBundle\HalloVerdenRequestEntityBundle::class => ['all' => true],
];
```

Usage
============

1. Create a class that extends `AbstractRequestEntity`. example:
   ```php
   <?php

   namespace App\Entity\Requests;
   
   use App\Entity\Yoo;
   use HalloVerden\RequestEntityBundle\Requests\AbstractRequestEntity;
   use JMS\Serializer\Annotation\ExclusionPolicy;
   use JMS\Serializer\Annotation\Expose;
   use JMS\Serializer\Annotation\SerializedName;
   use JMS\Serializer\Annotation\Type;
   use Symfony\Component\Validator\Constraints as Assert;

   #[ExclusionPolicy(ExclusionPolicy::ALL)]
   class TestRequest extends AbstractRequestEntity {
     #[SerializedName(name: 'test')]
     #[Type(name: 'array')]
     #[Expose()]
     private $test;
   
     #[SerializedName(name: 'yoo')]
     #[Type(name: 'App\Entity\Yoo')]
     #[Expose()]
     private Yoo $yoo;
   
     /**
      * @return array|null
      */
     public function getTest(): ?array {
       return $this->test;
     }
   
     /**
      * @inheritDoc
      */
     protected static function getRequestDataValidationFields(): array {
       return [
         'test' => [new Assert\Type(['type' => 'array']), new Assert\Count(['min' => 1])],
         'yoo' => new Assert\Collection([
           'fields' => [
             'message' => [new Assert\Type(['type' => 'string']), new Assert\NotBlank()]
           ],
           'allowMissingFields' => static::allowMissingFields(),
           'allowExtraFields' => static::allowExtraFields()
         ])
       ];
     }
   }
   ```
   Override the `getRequestDataValidationFields` method to validate the request data.
   These validation rules must match the incoming request which is an array (i.e. after decoding a json request)

 2. In your controller inject this request class using the ValueResolver. Example:
    ```php
    <?php
    
    namespace App\Controller;
    
    use App\Entity\Requests\TestRequest;
    use App\Entity\Response\TestResponse;
    use HalloVerden\RequestEntityBundle\ValueResolvers\RequestEntityResolver;
    use HalloVerden\ResponseEntityBundle\Controller\AbstractResponseEntityController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\Routing\Annotation\Route;
    
    #[Route(path: '/test2', name: 'testpost', methods: [Request::METHOD_POST])]
    class Test2Controller extends AbstractResponseEntityController {
      public function __invoke(
        #[ValueResolver(RequestEntityResolver::class)]
        TestRequest $testRequest
      ): JsonResponse {
        return $this->createJsonResponse(new TestResponse($testRequest->getTest()));
      }
    }
    ```
