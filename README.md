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
   use JMS\Serializer\Annotation as Serializer;
   use Symfony\Component\Validator\Constraints as Assert;
   
   /**
    * Class TestRequest
    *
    * @package App\Entity\Requests
    *
    * @Serializer\ExclusionPolicy("ALL")
    */
   class TestRequest extends AbstractRequestEntity {
   
     /**
      * @var array|null
      *
      * @Serializer\SerializedName("test")
      * @Serializer\Type(name="array")
      * @Serializer\Expose()
      */
     private $test;
   
     /**
      * @var Yoo
      *
      * @Serializer\SerializedName("yoo")
      * @Serializer\Type(name="App\Entity\Yoo")
      * @Serializer\Expose()
      */
     private $yoo;
   
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

 2. In your controller inject this request class using the paramconverter. Example:
    ```php
    <?php
    
    
    namespace App\Controller;
    
    
    use App\Entity\Requests\TestRequest;
    use App\Response\TestResponse;
    use HalloVerden\ResponseEntityBundle\Controller\AbstractResponseEntityController;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\Routing\Annotation\Route;
    
    /**
     * Class Test2Controller
     *
     * @package App\Controller
     *
     * @Route("/test2", methods={"POST"}, name="testpost")
     */
    class Test2Controller extends AbstractResponseEntityController {
    
      /**
       * @ParamConverter("testRequest", converter="HalloVerden\RequestEntityBundle\ParamConverter\RequestEntityConverter", class="App\Entity\Requests\TestRequest")
       *
       * @param TestRequest $testRequest
       *
       * @return JsonResponse
       */
      public function __invoke(TestRequest $testRequest): JsonResponse {
        return $this->createJsonResponse(new TestResponse($testRequest->getTest()));
      }
    
    }
    ```
