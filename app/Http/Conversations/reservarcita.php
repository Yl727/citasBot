<?php

namespace App\Http\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class reservarcita extends Conversation
{

    protected $mes;
    protected $dia;
    protected $hora;
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
       
        $this->preguntarMes();
    }
    public function preguntarMes()
    {
        $this->ask(" Ingrese el mes que quiere solicitar la cita", function(Answer $answer) {
            $this->mes =$answer->getText();
            $this->verificarMes();
        });
    }
    public function getMes($ind){
        $array= array("enero","febrero","marzo","abril","mayo","junio","julio",
                        "agosto","septiembre","octubre","noviembre","diciembre");
        return $array[$ind];                
    }
    public function verificarMes(){
       $min=0;
        $max=13;
        if(($this->mes)>$min and ($this->mes)<$max){
           
           $this->say("has elegido " . $this->getMes(($this->mes)-1));
           $this->preguntarDia();
        }else{
            $this->say("Debes ingresar un valor que este entre 1 y 12");
            $this->preguntarMes();
        }
    }
    public function preguntarDia()
    {
        $this->ask(" Ingrese el día que quiere solicitar la cita:", function(Answer $answer) {
            $this->dia=$answer->getText();
           
            $this->verificarDia();
        });
    }
    public function verificarDia()
    {
        if(($this->dia)>0 and ($this->dia)<31){
            
            $this->preguntarHorario();
        }
        else{
            $this->say("Debes ingresar un valor que este entre 1 y 31");
            $this->preguntarDia();
        }
    }
    public function preguntarHorario(){
        $question = Question::create('¿Qué horario desea solicitar?')
        ->addButtons([
            Button::create('10:00')->value('10:00'),
            Button::create('14:00')->value('14:00'),
            Button::create('15:00')->value('15:00'),
            Button::create('15:30')->value('15:30')
        ]);
        $this->ask($question, function (Answer $answer) {
        if ($answer->isInteractiveMessageReply()) {
            $this->hora=$answer->getText();
           
            $this->confirmarCita();
        }
        else
        {
            $this->say("Debes elegir una opción de las propuestas");
            $this->repeat();
        }
        });
    }
    
    public function confirmarCita()
    {
        $question = Question::create("Su cita sera reservada para $this->mes / $this->dia : $this->hora ¿Confirmar?")
        ->addButtons([
            Button::create('Si')->value('0'),
            Button::create('NO')->value('1')
        ]);
        $this->ask($question, function (Answer $answer) {
        if ($answer->isInteractiveMessageReply()) {
            $var=0;
            
            if(($answer->getValue())==$var)
            {
                $this->say("Tu cita a sido guardada exitosamente ");
                
            }else{
                $this->say("Tu cita a sido cancelada ");
                
            }
        }
        else
        {
            $this->say("Debes elegir una opción de las propuestas");
            $this->repeat();
        }
        });
    }
}
