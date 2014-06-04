<?php

/*
    Copyright 2014 Jessym Reziga https://github.com/jreziga/JRKPaymentSipsBundle

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

namespace JRK\PaymentSipsBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;



class JRKCommand extends ContainerAwareCommand
{
 
    protected function configure()
    {
        $this
            ->setName('jrk:sips:install')
            ->setDescription('Generate pathfile.')
            ->setDefinition(array(
                new InputArgument('param_directory', InputArgument::REQUIRED, 'The param directory'),
            ))
            ->setHelp(<<<EOT
The <info>jrk:sips:install</info> command creates the pathfile:

  <info>php app/console jrk:sips:install</info>

This interactive shell will ask you where is the api's param directory

EOT
            );
    }

    public function mkpath($path){
        $tree = explode("/",$path);
        $targetFolder = "";
        foreach($tree as $t){
            $targetFolder .= $t;
            if (!is_dir($targetFolder)) {mkdir($targetFolder, 0755);}
            $targetFolder .= "/";
        }
    }

    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path   = $input->getArgument('param_directory');

        $this->mkpath($path);

        $file = @fopen($path."/pathfile","w+");
        fwrite($file,"DEBUG!NO!\n");
        fwrite($file,"D_LOGO!/bundles/jrkpaymentsips/sips/logo/!\n");
        fwrite($file,"F_DEFAULT!".realpath("./")."/".$path."/parmcom.mercanet!\n");
        fwrite($file,"F_PARAM!".realpath("./")."/".$path."/parmcom!\n");
        fwrite($file,"F_CERTIFICATE!".realpath("./")."/".$path."/certif!\n");
        fwrite($file,"F_CTYPE!!\n");

        fclose($file);

        $output->writeln(sprintf('Pathfile generated', $path));
    }

    
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('param_directory')) {
            $path = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Param directory path [app/config/sips/param]: ',
                function($path) {
                    if (empty($path)) {
                       return "app/config/sips/param";
                    }

                    return $path;
                }
            );
            $input->setArgument('param_directory', $path);
        }
    }
}