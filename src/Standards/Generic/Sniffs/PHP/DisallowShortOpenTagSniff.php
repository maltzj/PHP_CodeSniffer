<?php

namespace PHP_CodeSniffer\Standards\Generic\Sniffs\PHP;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Generic_Sniffs_PHP_DisallowShortOpenTagSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Generic_Sniffs_PHP_DisallowShortOpenTagSniff.
 *
 * Makes sure that shorthand PHP open tags are not used.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class DisallowShortOpenTagSniff implements Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_OPEN_TAG,
                T_OPEN_TAG_WITH_ECHO,
               );

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens  = $phpcsFile->getTokens();
        $openTag = $tokens[$stackPtr];

        if ($openTag['content'] === '<?') {
            $error = 'Short PHP opening tag used; expected "<?php" but found "%s"';
            $data  = array($openTag['content']);
            $phpcsFile->addError($error, $stackPtr, 'Found', $data);
            $phpcsFile->recordMetric($stackPtr, 'PHP short open tag used', 'yes');
        } else {
            $phpcsFile->recordMetric($stackPtr, 'PHP short open tag used', 'no');
        }

        if ($openTag['code'] === T_OPEN_TAG_WITH_ECHO) {
            $nextVar = $tokens[$phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true)];
            $error   = 'Short PHP opening tag used with echo; expected "<?php echo %s ..." but found "%s %s ..."';
            $data    = array(
                        $nextVar['content'],
                        $openTag['content'],
                        $nextVar['content'],
                       );
            $phpcsFile->addError($error, $stackPtr, 'EchoFound', $data);
        }

    }//end process()


}//end class