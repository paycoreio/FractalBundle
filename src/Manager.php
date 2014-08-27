<?php

namespace SamJ\FractalBundle;

use League\Fractal\Manager as BaseManager;
use League\Fractal\Resource\ResourceAbstract;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Manager extends BaseManager implements ContainerAwareInterface {
	private $container;

	/**
	 * Sets the Container.
	 *
	 * @param ContainerInterface|null $container A ContainerInterface instance or null
	 */
	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}

	/**
	 * Create Data
	 *
	 * Main method to kick this all off. Make a resource then pass it over, and use toArray()
	 *
	 * @param \League\Fractal\Resource\ResourceAbstract $resource
	 * @param string $scopeIdentifier
	 * @param string $parentScopeInstance
	 * @return \League\Fractal\Scope
	 **/
	public function createData(ResourceAbstract $resource, $scopeIdentifier = null, $parentScopeInstance = null)
	{
		$scopeInstance = new Scope($this, $resource, $scopeIdentifier);
		$scopeInstance->setContainer($this->container);

		// Update scope history
		if ($parentScopeInstance !== null) {

			// This will be the new children list of parents (parents parents, plus the parent)
			$scopeArray = $parentScopeInstance->getParentScopes();
			$scopeArray[] = $parentScopeInstance->getCurrentScope();

			$scopeInstance->setParentScopes($scopeArray);
		}

		return $scopeInstance;
	}
} 