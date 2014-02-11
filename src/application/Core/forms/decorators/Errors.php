<?php
class Core_Decorator_Errors extends Zend_Form_Decorator_Abstract
{
    /**
     * Render errors
     *
     * @param  string $content
     * @return string
     */
    public function render($content)
    {
        $element = $this->getElement();
        $view    = $element->getView();
        if (null === $view) {
            return $content;
        }

        // Get error messages
        if ($element instanceof Zend_Form
            && null !== $element->getElementsBelongTo()
        ) {
            $errors = $element->getMessages(null, true);
        } else {
            $errors = $element->getMessages();
        }

        if (empty($errors)) {
            return $content;
        }

        $separator = $this->getSeparator();
        $placement = $this->getPlacement();
        $errors    = $view->formErrors($errors, $this->getOptions());

        switch ($placement) {
            case self::APPEND:
                return $content . $separator . $errors;
            case self::PREPEND:
                return $errors . $separator . $content;
        }
    }
}
