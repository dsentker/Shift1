<?php
namespace Shift1\Core\Console\Command;

use Shift1\Core\Console\Output;

class CreateCommand extends AbstractCommand {


    public function execute() {

        if(false === $this->getParameter(0) || false === $this->getParameter(1)) {
            return new Output\ColorOutput("<warn>What should i create?</warn>");
        }

        switch(\strtolower($this->getParameter(0))) {
            case 'action':

                $actionName = \lcfirst($this->getParameter(1)) . 'Action';

                $variableSet = new \Shift1\Core\VariableSet\VariableSet();
                $template = new \Shift1\Core\CodeWriter\CodeTemplate\CodeTemplate($variableSet);
                $template->setTemplateLocation('Libs/Shift1/Core/Resources/CodeTemplates/ControllerAction.php');
                $template->add('ACTIONNAME', $actionName);

                $writer = new \Shift1\Core\CodeWriter\MethodWriter('\\Application\\Controller\\PostController');
                $writer->injectCode($template);

                $writer->persist();

                print "Done.";

        }




    }

}
