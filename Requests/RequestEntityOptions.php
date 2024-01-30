<?php

namespace HalloVerden\RequestEntityBundle\Requests;

class RequestEntityOptions {
  private bool $combineQueryAndBody = false;
  private string|null $rootElement = null;

  /**
   * @return static
   */
  public static function create(): self {
    return new static();
  }

  /**
   * @return bool
   */
  public function combineQueryAndBody(): bool {
    return $this->combineQueryAndBody;
  }

  /**
   * @param bool $combineQueryAndBody
   *
   * @return self
   */
  public function setCombineQueryAndBody(bool $combineQueryAndBody): self {
    $this->combineQueryAndBody = $combineQueryAndBody;
    return $this;
  }

  /**
   * @return string|null
   */
  public function getRootElement(): ?string {
    return $this->rootElement;
  }

  /**
   * @param string|null $rootElement
   *
   * @return self
   */
  public function setRootElement(?string $rootElement): self {
    $this->rootElement = $rootElement;
    return $this;
  }

}
