<?php


namespace HalloVerden\RequestEntityBundle\Helpers;


use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Existence;

/**
 * Class CollectionConstraintHelper
 *
 * @package HalloVerden\RequestEntityBundle\Helpers
 */
class CollectionConstraintHelper {

  /**
   * @param Collection $collection
   *
   * @return array
   */
  public static function getFields(Collection $collection): array {
    $fields = [];

    foreach ($collection->fields as $field => $constraint) {
      if (\is_array($constraint) && $nestedCollection = self::getCollectionConstraint($constraint)) {
        $fields[$field] = self::getFields($nestedCollection);
      } elseif ($constraint instanceof Existence && $nestedCollection = self::getCollectionConstraint($constraint->constraints)) {
        $field[$field] = self::getFields($nestedCollection);
      } elseif ($constraint instanceof Collection) {
        $field[$field] = self::getFields($constraint);
      } else {
        $fields[] = $field;
      }
    }

    return $fields;
  }

  /**
   * @param array $constraints
   *
   * @return Collection|null
   */
  private static function getCollectionConstraint(array $constraints): ?Collection {
    foreach ($constraints as $constraint) {
      if ($constraint instanceof Collection) {
        return $constraint;
      }
    }

    return null;
  }

}
