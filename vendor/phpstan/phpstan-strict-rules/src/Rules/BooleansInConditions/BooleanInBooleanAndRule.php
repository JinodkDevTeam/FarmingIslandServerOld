<?php declare(strict_types = 1);

namespace PHPStan\Rules\BooleansInConditions;

use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PHPStan\Node\BooleanAndNode;
use PHPStan\Type\VerbosityLevel;

/**
 * @implements \PHPStan\Rules\Rule<BooleanAndNode>
 */
class BooleanInBooleanAndRule implements \PHPStan\Rules\Rule
{

	/** @var BooleanRuleHelper */
	private $helper;

	/** @var bool */
	private $checkLogicalAndConstantCondition;

	public function __construct(BooleanRuleHelper $helper, bool $checkLogicalAndConstantCondition = false)
	{
		$this->helper = $helper;
		$this->checkLogicalAndConstantCondition = $checkLogicalAndConstantCondition;
	}

	public function getNodeType(): string
	{
		return BooleanAndNode::class;
	}

	public function processNode(\PhpParser\Node $node, \PHPStan\Analyser\Scope $scope): array
	{
		$originalNode = $node->getOriginalNode();
		if (!$originalNode instanceof BooleanAnd && !$this->checkLogicalAndConstantCondition) {
			return [];
		}

		$messages = [];
		if (!$this->helper->passesAsBoolean($scope, $originalNode->left)) {
			$leftType = $scope->getType($originalNode->left);
			$messages[] = sprintf(
				'Only booleans are allowed in &&, %s given on the left side.',
				$leftType->describe(VerbosityLevel::typeOnly())
			);
		}

		$rightScope = $node->getRightScope();
		if (!$this->helper->passesAsBoolean($rightScope, $originalNode->right)) {
			$rightType = $rightScope->getType($originalNode->right);
			$messages[] = sprintf(
				'Only booleans are allowed in &&, %s given on the right side.',
				$rightType->describe(VerbosityLevel::typeOnly())
			);
		}

		return $messages;
	}

}
