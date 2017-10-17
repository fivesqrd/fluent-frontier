<?php
namespace Fluent;

class Body
{
    protected $_title;
    
    protected $_teaser;
    
    protected $_content;

    public function __construct($options = array())
    {
        $this->_options = array_merge($this->_defaults, $options);

        $xml = new \DOMDocument();
        $xml->appendChild(new \DOMElement('content'));

        $this->_content = $xml->childNodes->item(0);
    }
    
    /**
     * @param string $text
     * @return \Fluent\Message\Content\Markup
     */
    public function title($text)
    {
        $this->_title = $text;
        return $this;
    }
    
    /**
     * @param string $text
     * @return \Fluent\Message\Content\Markup
     */
    public function paragraph($text)
    {
        $element = new \DOMElement('paragraph');
        $this->_content->appendChild($element);
        $element->appendChild($this->_getCData($text));
        return $this;
    }

    /**
     * @param string $text
     * @return \Fluent\Message\Content\Markup
     */
    public function segment($string)
    {
        $element = new \DOMElement('paragraph');
        $this->_content->appendChild($element);
        $element->appendChild($this->_getCData($string));
        return $this;
    }

    /**
     * @param array $numbers Up to 3 number/caption pairs
     * @return \Fluent\Message\Content\Markup
     */
    public function numbers($numbers)
    {
        $parent = $this->_content
            ->appendChild(new \DOMElement('numbers'));

        foreach ($numbers as $number) {
            $element = $this->_getNumberElement(
                $parent->appendChild(new \DOMElement('number')), $number
            );
        }

        return $this;
    }
    
    /**
     * @param array $number A number/caption pair
     * @return \Fluent\Message\Content\Markup
     */
    public function number($number)
    {
        if (is_string($number) || is_numeric($number)) {
            /* we have been given a number only */
            $number = array('value' => $number);
        }

        if (!array_key_exists('value', $number)) {
            throw new Exception('Number requires a value element');
        }
        
        $parent = $this->_content
            ->appendChild(new \DOMElement('numbers'));

        $element = $this->_getNumberElement(
            $parent->appendChild(new \DOMElement('number')), $number
        );

        return $this;
    }

    /**
     * @param string $href
     * @param string $text
     * @return \Fluent\Message\Content\Markup
     */
    public function button($href, $text)
    {
        $element = new \DOMElement('button', htmlentities($text));
        $this->_content->appendChild($element);
        $element->setAttribute('href', $href);
        return $this;
    }

    public function teaser($text)
    {
        $this->_teaser = $text;
        return $this;
    }
    
    public function getTeaser()
    {
        return $this->_teaser;
    }
    
    /**
     * @return string
     */
    public function toString()
    {
        if ($this->_title) {
            $this->_content->appendChild(
                new \DOMElement('title', htmlentities($this->_title))
            );
        }

        $doc = $this->_content->ownerDocument;
        return $doc->saveXml();
    }
    
    protected function _getNumberElement($element, $number)
    {
        if (array_key_exists('value', $number)) {
            $element->appendChild(
                new \DOMElement('value', htmlentities($number['value']))
            );
        }

        if (array_key_exists('caption', $number)) {
            $element->appendChild(
                new \DOMElement('caption', $number['caption'])  
            );
        }

        return $element;
    }

    protected function _getCData($text)
    {
        return $this->_content->ownerDocument->createCDATASection($text);
    }
    
    public function __toString()
    {
        return $this->toString();
    }
}
