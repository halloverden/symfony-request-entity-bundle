<?php


namespace HalloVerden\RequestEntityBundle\Traits;


use Symfony\Component\Validator\Constraints\Collection;

/**
 * Useful to prevent recreating the collection constraint on every call to getCollectionConstraint.
 *
 * Trait RequestEntityCollectionConstraintTrait
 *
 * @package HalloVerden\RequestEntityBundle\Traits
 */
trait RequestEntityCollectionConstraintTrait {

  /**
   * @var Collection|null
   */
  private static $_collectionConstraint = null;

  /**
   * @return Collection
   */
  abstract protected static function createCollectionConstraint(): Collection;

  /**
   * @return Collection|null
   */
  protected static function getCollectionConstraint(): ?Collection {
    return self::$_collectionConstraint ?: self::$_collectionConstraint = static::createCollectionConstraint();
  }

}
