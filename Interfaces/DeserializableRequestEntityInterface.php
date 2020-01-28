<?php


namespace HalloVerden\RequestEntityBundle\Interfaces;


interface DeserializableRequestEntityInterface {

  /**
   * Expects an array of key => value representing key to be deserialized, and which type to deserialize to
   * @return array
   */
  public function getDeserializableProperties(): array;

  /**
   * Called when the deserializer needs the value to deserialize for the given propery
   *
   * @param string $propertyName
   *
   * @return mixed
   */
  public function getDeserializablePropertyValue(string $propertyName);

  /**
   * Called when the value for a given property has been deserialized
   *
   * @param string $propertyName
   * @param $value
   */
  public function setDeserializedValue(string $propertyName, $value): void;

}
