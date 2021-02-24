<?php declare(strict_types = 1);

namespace PHPStan\Rules\BooleansInConditions;

use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PHPStan\Node\BooleanOrNode;
use PHPStan\Type\VerbosityLevel;

/**
 * @implements \PHPStan\Rules\Rule<BooleanOrNode>
 */
class BooleanInBooleanOrRule implements \PHPStan\Rules\Rule
{

	/** @var BooleanRuleHelper */
	private $helper;

	/** @var bool */
	private $checkLogicalOrConstantCondition;

	public function __construct(BooleanRuleHelper $helper, bool $checkLogicalOrConstantCondition = false)
	{
		$this->helper = $helper;
		$this->checkLogicalOrConstantCondition = $checkLogicalOrConstantCondition;
	}

	public function getNodeType(): string
	{
		return BooleanOrNode::class;
	}

	public function processNode(\PhpParser\Node $node, \PHPStan\Analyser\Scope $scope): array
	{
		$originalNode = $node->getOriginalNode();
		if (!$originalNode instanceof BooleanOr && !$this->checkLogicalOrConstantCondition) {
			return [];
		}

		$messages = [];
		if (!$this->helper->passesAsBoolean($scope, $originalNode->left)) {
			$leftType = $scope->getType($originalNode->left);
			$messages[] = sprintf(
				'Only booleans are allowed in ||, %s given on the left side.',
				$leftType->describe(VerbosityLevel::typeOnly())
			);
		}

		$rightScope = $node->getRightScope();
		if (!$this->helper->passesAsBoolean($rightScope, $originalNode->right)) {
			$rightType = $rightScope->getType($originalNode->right);
			$messages[] = sprintf(
				'Only booleans are allowed in ||, %s given on the right side.',
				$rightType->describe(VerbosityLevel::typeOnly())
			);
		}

		return $messages;
	}

}
