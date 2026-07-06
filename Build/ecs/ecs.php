<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\Basic\SingleLineEmptyBodyFixer;
use PhpCsFixer\Fixer\CastNotation\CastSpacesFixer;
use PhpCsFixer\Fixer\Comment\HeaderCommentFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Operator\NotOperatorWithSuccessorSpaceFixer;
use PhpCsFixer\Fixer\Operator\OperatorLinebreakFixer;
use PhpCsFixer\Fixer\Operator\UnaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer;
use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\Strict\StrictComparisonFixer;
use PhpCsFixer\Fixer\Whitespace\MethodChainingIndentationFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayOpenerAndCloserNewlineFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\StandaloneLineInMultilineArrayFixer;
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\CodingStandard\Fixer\Spacing\MethodChainingNewlineFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/../../Build',
        __DIR__ . '/../../Classes',
        __DIR__ . '/../../Configuration',
        __DIR__ . '/../../ext_emconf.php',
    ])
    ->withSets([
        SetList::PSR_12,
        SetList::CLEAN_CODE,
        SetList::ARRAY,
        SetList::COMMON,
        SetList::COMMENTS,
        SetList::CONTROL_STRUCTURES,
        SetList::DOCBLOCK,
        SetList::NAMESPACES,
        SetList::SPACES,
    ])
    ->withConfiguredRule(GeneralPhpdocAnnotationRemoveFixer::class, [
        'annotations' => ['author', 'package', 'group'],
    ])
    ->withConfiguredRule(NoSuperfluousPhpdocTagsFixer::class, [
        'allow_mixed' => true,
    ])
    ->withConfiguredRule(CastSpacesFixer::class, [
        'space' => 'single',
    ])
    ->withConfiguredRule(LineLengthFixer::class, [
        LineLengthFixer::INLINE_SHORT_LINES => false,
    ])
    ->withConfiguredRule(HeaderCommentFixer::class, [
        'header' => <<<EOF
This file is part of the TYPO3 CMS project.

(c) Christoph Lehmann, Simon Schaufelberger

It is free software; you can redistribute it and/or modify it under
the terms of the GNU General Public License, either version 2
of the License, or any later version.

For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.

The TYPO3 project - inspiring people to share!
EOF
    ])
    ->withRules([
        // Rules that are not in a set
        OperatorLinebreakFixer::class,
        SingleLineEmptyBodyFixer::class,
        NoUnusedImportsFixer::class,
        ArraySyntaxFixer::class,
        StandaloneLineInMultilineArrayFixer::class,
        ArrayOpenerAndCloserNewlineFixer::class,
        DeclareStrictTypesFixer::class,
        LineLengthFixer::class,
    ])
    ->withSkip([
        LineLengthFixer::class,
        DeclareStrictTypesFixer::class => [
            __DIR__ . '/../../ext_emconf.php',
        ],
        NotOperatorWithSuccessorSpaceFixer::class,

        StrictComparisonFixer::class,

        /*HeaderCommentFixer::class => [
            __DIR__ . '/../ecs/ecs.php',
            __DIR__ . '/../fractor/fractor.php',
            __DIR__ . '/../rector/rector.php',
            __DIR__ . '/../../ext_emconf.php',
        ],*/
        HeaderCommentFixer::class,

        UnaryOperatorSpacesFixer::class,
        MethodChainingIndentationFixer::class,
        MethodChainingNewlineFixer::class,
    ]);
