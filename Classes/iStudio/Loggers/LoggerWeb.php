<?php

namespace iStudio\Loggers;

use iStudio\BattleResult;
use iStudio\Interfaces\BattleLogger;

/**
 * The web logger simply outputs all the battle messages in the browser.
 * The messages will be color coded.
 *
 * Class LoggerWeb
 * @package iStudio
 */
class LoggerWeb implements BattleLogger
{

    /** @var array */
    protected $aPrefixes = array(
        BattleLogger::TYPE_HIT => 'Hit',
        BattleLogger::TYPE_MISS => 'Miss',
        BattleLogger::TYPE_DEATH => 'Death',
        BattleLogger::TYPE_MOVE => 'Move',
        BattleLogger::TYPE_INSANE => 'Insanity'
    );

    /** @var array */
    protected $aStyles = array(
        BattleLogger::TYPE_HIT => 'color: green',
        BattleLogger::TYPE_MISS => 'color: red',
        BattleLogger::TYPE_DEATH => 'color: black; text-decoration: underline',
        BattleLogger::TYPE_MOVE => 'color: grey',
        BattleLogger::TYPE_INSANE => 'color: orange; font-weight:bold; text-decoration: underline'
    );

    /**
     * Logs a misc message
     * @param string $sMessage
     * @return BattleLogger
     */
    function logOther($sMessage)
    {
        echo '<p>' . $sMessage . '</p>';
        return $this;
    }

    /**
     * Logs a battle result
     * @param BattleResult $oResult
     * @return $this
     */
    function logResult(BattleResult $oResult)
    {
        $sMessage = '<div style="display: inline-block; '.$this->aStyles[$oResult->type].'"><h3 style="display:inline-block">'.$this->aPrefixes[$oResult->type].'!</h3> ';
        $sMessage .= $oResult->attacker.' ';
        switch ($oResult->type) {
            case (BattleLogger::TYPE_HIT) :
                $sMessage .= $oResult->message.'. '.ucfirst($oResult->defender);
                $sMessage .= ' takes '.$oResult->amount.' damage.';
                break;
            case (BattleLogger::TYPE_MISS) :
                $sMessage .= $oResult->message.'. '.ucfirst($oResult->defender);
                $sMessage .= ' is safe.';
                break;
            case (BattleLogger::TYPE_DEATH) :
                $sMessage .= 'causes '.$oResult->amount.' damage and '.$oResult->message.' '.ucfirst($oResult->defender).'!!';
                break;
            default:
                break;
        }
        echo $sMessage.'</div><br/>';
        return $this;
    }

    /**
     * Logs an array of passed results, automatically picking the proper method
     * @param $aResults
     * @return $this
     */
    function logMultiple(array $aResults) {
        foreach ($aResults as $oResult) {
            if (is_string($oResult)) {
                $this->logOther($oResult);
            } else if ($oResult instanceof BattleResult) {
                $this->logResult($oResult);
            }
        }
        return $this;
    }

}