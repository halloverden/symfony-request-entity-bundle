<?php


namespace HalloVerden\RequestEntityBundle\Requests;

/**
 * Class RequestEntityOptions
 *
 * @package HalloVerden\RequestEntityBundle\Requests
 */
class RequestEntityOptions {
  const DEFAULT_OPTIONS = [
    'combineQueryAndBody' => false,
    'preventEntireBody' => false,
    'rootElement' => null,
    'throwViolations' => true,
    'validateEntity' => true
  ];

  /**
   * @var boolean
   */
  private $combineQueryAndBody;

  /**
   * @var boolean
   */
  private $preventEntireBody;

  /**
   * @var string|null
   */
  private $rootElement;

  /**
   * @var boolean
   */
  private $throwViolations;

  /**
   * @var boolean
   */
  private $validateEntity;

  public function __construct( array $requestEntityOptions = [] ) {
    foreach (self::DEFAULT_OPTIONS as $option => $defaultValue) {
      if(isset($requestEntityOptions[$option])) {
        $this->{'set' . ucfirst($option)}($requestEntityOptions[$option]);
      } else {
        $this->{$option} = self::DEFAULT_OPTIONS[$option];
      }
    }
  }

  /**
   * @param bool $combineQueryAndBody
   * @return RequestEntityOptions
   */
  private function setCombineQueryAndBody( $combineQueryAndBody ): self {
    $this->combineQueryAndBody = (bool) $combineQueryAndBody;
    return $this;
  }

  /**
   * @param bool $preventEntireBody
   * @return RequestEntityOptions
   */
  private function setPreventEntireBody( $preventEntireBody ): self {
    $this->preventEntireBody = (bool) $preventEntireBody;
    return $this;
  }

  /**
   * @param string|null $rootElement
   * @return RequestEntityOptions
   */
  private function setRootElement( $rootElement ): self {
    $this->rootElement = (string) $rootElement;
    return $this;
  }

  /**
   * @param bool $validateEntity
   * @return RequestEntityOptions
   */
  private function setValidateEntity( $validateEntity ): self {
    $this->validateEntity = (bool) $validateEntity;
    return $this;
  }

  /**
   * @param bool $throwViolations
   * @return RequestEntityOptions
   */
  private function setThrowViolations( $throwViolations ): self {
    $this->throwViolations = (bool) $throwViolations;
    return $this;
  }

  /**
   * @return string|null
   */
  public function getRootElement(): ?string {
    return $this->rootElement;
  }

  /**
   * @return bool
   */
  public function combineQueryAndBody(): bool {
    return $this->combineQueryAndBody;
  }

  /**
   * @return bool
   */
  public function preventEntireBody(): bool {
    return $this->preventEntireBody;
  }

  /**
   * @return bool
   */
  public function isThrowViolations(): bool {
    return $this->throwViolations;
  }

  /**
   * @return bool
   */
  public function isValidateEntity(): bool {
    return $this->validateEntity;
  }
}
