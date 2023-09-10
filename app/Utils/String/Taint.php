<?php

namespace App\Utils\String;

/**
 * @description
 * The approach attempts to split the sink value string into an array of 
 * substrings or tokens with metadata (e.g., position of the substring in 
 * the sink value, whether the token is tainted and to what extent (similarity)).
 *
 * Example:
 * 
 * $inputValue = 'foobar';
 * $sinkValue = 'SELECT * FROM Users WHERE Name="' . $inputValue . '"';
 *
 * This will be split into the following tokens:
 *
 * [ ['value' => 'SELECT', 'position' => 0, 'tainted' => false, 'similarity' => ... ],
 *   ['value' => '*', 'position' => 7, 'tainted' => false, 'similarity' => ... ],
 *   ...
 *   ['value' => '"foobar"', 'position' => 31 , 'tainted' => true, 'similarity' => ... ]    
 * ]
 *
 * Note that the approach that I'm experimenting with here is not using any SQL parser
 * as I wanted to test how far I can come without using one and with only relying on what
 * the framework/language provides. That said, I needed a way to tokenize the sink value 
 * so I'm doing that based on common characters that separate tokens from each other such 
 * as the whitespace character (e.g, 'SELECT * FROM') or equal character (e.g., 'WHERE 
 * title="foo"). 
 * 
 * This approach, while simple and naive, has its limitations. For example, it will also 
 * show tokens of the sink value as tainted which are not originating from the user. 
 * This is the case when you use SQL keywords as an input value:
 *
 * $inputValue = 'FROM';
 * $sinkValue = 'SELECT * FROM Users WHERE Name="' . $inputValue . '"';
 *                        ^^^^                        
 *                        The 'FROM' here will also be marked as tainted although it's not 
 *                        possible for the user to take control of it
 *
 * Another limitation is when one of the separator characters that I'm looking for is also within
 * the input value (e.g., '=foo bar' contains both equal character and a whitespace character). 
 * This can result in '=foo bar' tokenized into ['=','foo','bar'] which could then lead to a lower
 * similarity score when comparing, e.g., 'foo' with '=foo bar'.
 *
 * However, to overcome these limitations we can use an input value:
 * a) that does not include any SQL keyword 
 * b) that does not include any separator character
 *
 * such as _0x741n7_ or {{TAINT_TAG}}
 *
 * It seems that Acunetix AcuSensor makes use of a similar approach in which values are wrapped 
 * around ACUSTART<value>ACUEND to signal that an input value originates from the security scanner
 * for further investigation.
 * 
 * @feedback
 * Any further ideas, issues and discussions around this approach are welcome :) you can find my 
 * contact details on my webpage - https://tolgadevsec.github.io.
 */
class Taint 
{
    private static function IsWhitespace(string $character) : bool
    {
        return $character === ' ';
    }

    private static function IsSeparator(string $character) : bool
    {
        return in_array($character, ['=',','], true);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function Infer(string $inputValue, string $sinkValue, int $similarityThreshold = 50) : array 
    {
        $tokenCharacters = [];
        $position = 0;
        $pushingCharacters = false;

        $sinkValue = str_split($sinkValue); 
        $sinkValueLength = count($sinkValue);
        $sinkValueTokens = [];

        for($i=0;$i<$sinkValueLength;$i++)
        {
            if(self::IsWhitespace($sinkValue[$i]))
            {
                if(!$pushingCharacters)
                {
                    continue;
                }

                $sinkValueToken = implode($tokenCharacters);

                similar_text($sinkValueToken, $inputValue, $similarity);

                $sinkValueTokens[] = [
                    'value' => $sinkValueToken,
                    'position' => $position,
                    'tainted' => $similarity >= $similarityThreshold,
                    'similarity' => $similarity
                ];

                $position = $i+1;

                $pushingCharacters = false;
                $tokenCharacters = [];
            }
            elseif(self::IsSeparator($sinkValue[$i]))
            {
                $separatorCharacter = $sinkValue[$i];
                $sinkValueToken = implode($tokenCharacters);
                $includeSeperatorToken = true;

                if($sinkValueToken === '')
                {
                    $includeSeperatorToken = false;
                    $sinkValueToken = $separatorCharacter;
                }

                similar_text($sinkValueToken, $inputValue, $similarity);

                $sinkValueTokens[] = [
                    'value' => $sinkValueToken,
                    'position' => $position,
                    'tainted' => $similarity >= $similarityThreshold,
                    'similarity' => $similarity
                ];

                $position = $position+mb_strlen($sinkValueToken);

                if($includeSeperatorToken){
                    $sinkValueTokens[] = [
                        'value' => $sinkValue[$i],
                        'position' => $position,
                        'tainted' => false,
                        'similarity' => 0
                    ];
                
                    $position = $i+1; 
                }

                $pushingCharacters = false;
                $tokenCharacters = [];
            }
            else 
            {
                $tokenCharacters[] = $sinkValue[$i];
                $pushingCharacters = true;
            }
        }

        if(!empty($tokenCharacters))
        {
            $sinkValueToken = implode($tokenCharacters);

            similar_text($sinkValueToken, $inputValue, $similarity);

            $sinkValueTokens[] = [
                'value' => $sinkValueToken,
                'position' => $position,
                'tainted' => $similarity >= $similarityThreshold,
                'similarity' => $similarity
            ];
        }

        return $sinkValueTokens;
    }
}