<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 *
 * @package     mod_findpartner
 * @category    string
 * @copyright   2020 Rodrigo Aguirregabiria Herrero, Manuel Alfredo Collado Centeno, GIETA UPM
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Find your partner';
$string['modulename'] = 'Find your partner';
$string['modulename_help'] = '<p>Permite a los participantes crear y unirse a grupos. Características: </p><ul><li>El alumno puede crear grupos y darles una descripción</li><li>Los alumnos pueden solicitar unirse a un grupo</li><li>Los grupos pueden ser autocompletados</li><li>Los estudiantes pueden crear contratos</li></ul>';
$string['modulename_link'] = 'mod/findpartner/view';
$string['findpartnername'] = 'Find your partner';
$string['findpartnersettings'] = 'Opciones';
$string['findpartnerfieldset'] = 'Definir campos';
$string['findpartnername_help'] = 'Nombre de la actividad';
$string['minmembers'] = 'Mínimo de miembros en un grupo';
$string['maxmembers'] = 'Máximo de miembros en un grupo';
$string['dateclosuregroups'] = 'Fecha en la que los estudiantes ya no pueden unirse a otros grupos';
$string['error_minmembers'] = 'Error: un grupo debe estar formado por dos o más miembros.';
$string['error_maxmembers'] = 'Error: el número máximo de miembros debe ser superior o igual al número mínimo de miembros.';
$string['group_name'] = 'Nombre el grupo.';
$string['group_description'] = 'Descripción del grupo.';
$string['creategroup'] = 'Crear grupo';
$string['maxcharlenreached'] = 'Tamaño máximo superado.';
$string['description'] = 'Descripción';
$string['members'] = 'Miembros';
$string['request'] = 'Mensaje de la petición';
$string['send'] = 'Enviar mensaje';
$string['send_request'] = 'Enviar petición';
$string['inagroup'] = 'Ya estás dentro de un grupo. No puedes crear un grupo.';
$string['viewrequest'] = 'Ver peticiones';
$string['requestmessage'] = 'Mensaje de petición';
$string['accept'] = 'Aceptar';
$string['deny'] = 'Denegar';
$string['cancel'] = 'Cancelar';
$string['joinmessage'] = 'Si pulsas el siguiente botón estás aceptando unirte a esta actividad para encontrar compañeros de grupo. En caso de que no encuentres un compañero el sistema te emparejará automaticamente.';
$string['exitactivity'] = 'Salir de la actividad';
$string['exitgroup'] = 'Salir del grupo';
$string['groupnameexists'] = 'Ya hay otro grupo con este nombre. Por favor, introduce otro nombre.';
$string['groupfull'] = 'El grupo está lleno, no puedes acpetar más peticiones.';
$string['norequest'] = 'No quedan peticiones pendientes.';
$string['viewgroup'] = 'Ver grupo';
$string['userid'] = 'Id de usuario';
$string['firstname'] = 'Nombre';
$string['lastname'] = 'Apellido';
$string['email'] = 'email';
$string['enrolstudents'] = 'Inscribir estudiantes';
$string['enrol'] = 'Inscribir';
$string['deenrolstudents'] = 'Desinscribir estudiantes';
$string['deenrol'] = 'Desinscribir';
$string['duedate'] = 'Fecha límite para unirse a grupos';
$string['autogroup'] = 'Tarea autogroup';
$string['alertcontract'] = 'Tenéis 24 horas para decidir entre todos los miembros del grupo si queréis hacer contratos.';
$string['contractyes'] = 'Quiero hacer un contrato';
$string['contractno'] = 'No quiero hacer un contrato';
$string['task'] = 'Contenido de la tarea';
$string['create_block'] = 'Crear un bloque de trabajo';
$string['createblock'] = 'Crar bloque';
$string['membersform'] = 'Miembros a asignar al bloque de trabajo';
$string['membershelp'] = 'Puedes seleccionar más de un estudiante a un bloque de trabajo. Para hacer esto tienes que seleccionar los usuarios que quieres asignar presionando con el botón izquierdo del ratón + ctrl (command en mac).';
$string['membershelp_help'] = 'Puedes seleccionar más de un estudiante a un bloque de trabajo. Para hacer esto tienes que seleccionar los usuarios que quieres asignar presionando con el botón izquierdo del ratón + ctrl (command en mac).';
$string['workblock'] = 'Tarea';
$string['memberstable'] = 'Miembros asignados a la tarea';
$string['workblockstatus'] = 'Estado';
$string['edit'] = 'Editar';
$string['complain'] = 'Emitir queja';
$string['complains'] = 'Quejas';
$string['sendcomplain'] = 'Enviar queja';
$string['accepted'] = 'Aceptado';
$string['dennied'] = 'Denegada, el administrador puede editarlo.';
$string['pending'] = 'Pendiente de aprobación';
$string['verified'] = 'Verificada, la tarea está hecha correctamente';
$string['complete'] = 'Completado, pendiente de validación';
$string['done'] = 'Hecho';
$string['verify'] = 'La tarea está hecha';
$string['noverify'] = 'La tarea no está hecha';
$string['viewcontracts'] = 'Ver contratos';
$string['edited'] = 'Este bloque de trabajo ha sido editado. Los estudiantes crearon una nueva versión';
$string['datecreation'] = 'Fecha de creación del bloque de trabajo';
$string['date'] = 'Fecha';
$string['time'] = 'Hora';
$string['datemodified'] = 'Fecha del último cambio de estado';
$string['contacttype'] = 'Tipo de contacto';
$string['contactmethod'] = 'Contacto';
$string['contactmethodhelp'] = 'Por ejemplo: estudiante@universidad.es, @usuario ';
$string['contacttypehelp'] = 'Tipo de contacto (email, twitter, etc)';
$string['mandatorycontact'] = 'Tienes que añadir un método de contacto para así poder comunicarte con tu grupo.';
$string['showcontact'] = 'Tu información de contacto sólo será visible por los miembros de tu grupo.';
$string['save'] = 'Guardar';
$string['viewmembers'] = 'Ver miembros y su información de contacto';
$string['editcontact'] = 'Editar información de contacto';
$string['useemail'] = 'Quiero utilizar el email oficial';
$string['enddate'] = 'Fecha de finalización';
$string['endactivitydate'] = 'Después de esta fecha los estudiantes no podrán editar bloques de trabajo';
$string['alreadysent'] = 'Enviada';
$string['minimum'] = 'mínimo';
$string['whatcontracts'] = '¿Qué son los contratos?';
$string['whencontracts'] = '¿Cuándo están disponibles los contratos?';
$string['whycontracts'] = '¿Por qué motivos usar los contratos?';
$string['howcontracts'] = '¿Cómo funcionan los contratos?';
$string['whatcontractstext'] = 'Es un acuerdo que recoje los deberes de los miembros del grupo. Divide la tarea a hacer por el grupo.';
$string['whencontractstext'] = 'Una vez llegue la fecha límite, los estudiantes deben decidir si quieren usar esta funcionalidad. La mitad o más de los votos deben ser sí para acceder a esta funcionalidad.';
$string['whycontractstext'] = 'El objetivo de este proyecto es dar herramientas a los estudianets para el trabajo en equipo. Los estudiantes deben tener buena comunicación para alcanzar el éxito. Queremos evitar los casos de miembros en un grupo que no trabajan. Los profesores a su vez tienen problemas para evaluar el trabajo e equipo, por ello tedrán acceso a la información sobre contratos (miembros del grupo, los bloques de trabajo, quejas etc';
$string['howcontractstext'] = 'El administrador del grupo puede crear bloques de trabajo. Un bloque de trabajo es una tarea compuesta por un nombre, los miembros encargados de la tarea, y un estado (pendiente de aprobación, aceptado, completado o  validado). <br>Cada bloque de trabajo debe ser votado por todos los miembros para ser aceptado. <br> Un bloque de trabajo aceptado puede ser marcado como completado por cualquier miembro encargado de ese bloque. <br>Un bloque de trabajo debe ser votado como verificado por el resto del grupo. <br>Cualquier miembro del grupo puede enviar una queja cuando lo desee a no ser que el bloque de trabajo ya esté verificado. <br>Cuando un bloque de trabajo tenga al menos una queja o esté denegado, el administrador lo podrá editar.';
$string['autogenerated'] = 'Grupo generado automáticamente';
$string['enrolall'] = 'Inscribir a todos los estudiantes';
$string['completegroups'] = 'Autocompletar grupos';
$string['autocompleteclose'] = 'Autocompletar y cerrar grupos';
$string['buttonfunction'] = 'El botón de la izquierda completará los grupos, pero los estudiantes podrán seguir moviendose entre ellos. <br> EL botón de la derecha completará los grupos y los cerrará definitivamente.';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';
$string[''] = '';