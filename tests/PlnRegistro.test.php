<?php
$nombres_fix = array();



$arr_nombres = array(
	array('Carlos Eduardo', 'Alquijay Ajau', 'TERCERO A'),
	array('Iris Magaly', 'Asturias Chunchun,', 'TERCERO A'),
	array('Carlos Alexander', 'Cajbon Felipe', 'TERCERO A'),
	array('Oliver Leonardo', 'Castro Rucal', 'TERCERO A'),
	array('William Fernando', 'Choreque Sula', 'TERCERO A'),
	array('Kevin Alexander', 'Choreque Xunic', 'TERCERO A'),
	array('Sara Elizabeth', 'Coc Rucal', 'TERCERO A'),
	array('Cesar Abdiel', 'Otzoy  Caal', 'TERCERO A'),
	array('Angela Elizabeth', 'Quisque Imuchac', 'TERCERO A'),
	array('Kevin Eduardo', 'Quisquinay Xetey', 'TERCERO A'),
	array('Kimberly Jessenia', 'Reguan Rabay', 'TERCERO A'),
	array('Kevin Enrique', 'Rucal Ajin', 'TERCERO A'),
	array('Lesly Adalinda', 'Rucal Tubac', 'TERCERO A'),
	array('Dulce Maria', 'Rucal Turuy', 'TERCERO A'),
	array('Luis Armando', 'Solloy Perez', 'TERCERO A'),
	array('Bryan Alexander', 'Sul Imuchac', 'TERCERO A'),
	array('Elmer Misael', 'Sula Quisquinay', 'TERCERO A'),
	array('Jonathan Alexander', 'Tajin Raczan', 'TERCERO A'),
	array('Ingrid Jeaneth', 'Tomas Xiquin', 'TERCERO A'),
	array('Elmer Eduardo', 'Yol Gil', 'TERCERO A'),
	array('Javier Angel', 'Alquijay Reguan', 'TERCERO A'),
	array('Jaime', 'Asturias Alquijay', 'TERCERO A'),
	array('Blanca Maricela', 'Chiquito Ajuchan', 'TERCERO A'),
	array('Gerson Estuardo', 'Chiquito Tubac', 'TERCERO A'),
	array('Luis Armando', 'Culajay maguey', 'TERCERO A'),
	array('Kelly Lourdes', 'Felipe Chile', 'TERCERO A'),
	array('Wilmer Alexander', 'Garcia Rucal', 'TERCERO A'),
	array('Jose Elmer', 'Laroj Rajpop', 'TERCERO A'),
	array('Franklin Anderson', 'Quisquinay Gil', 'TERCERO A'),
	array('Miguel Angel', 'Raczan Chiquito', 'TERCERO A'),
	array('Jonathan Ediel', 'Solis Pirir', 'TERCERO A'),
	array('Blanca Estela', 'Tejaxun Yancis', 'TERCERO A'),
	array('Luisa Mariela', 'Tomas Cubur', 'TERCERO A'),
	array('Medelyn Janeth', 'Xulu Solis', 'TERCERO A'),
	array('Byron', 'Alquijay Ajau', 'TERCERO B'),
	array('Sara Angelica', 'Asturias Burrion', 'TERCERO B'),
	array('Daniel Alexander', 'Burrion Felipe', 'TERCERO B'),
	array('Anahi', 'Chiquito Quisque', 'TERCERO B'),
	array('Keyla', 'Choreque Rucal', 'TERCERO B'),
	array('Juan Diego', 'Choreque Sula', 'TERCERO B'),
	array('Isela Eunice', 'Chuy Chiquito', 'TERCERO B'),
	array('Edwin Arturo', 'Colchaj Felipe', 'TERCERO B'),
	array('Marcos Noe', 'Cubur Gil', 'TERCERO B'),
	array('Mynor Alexander', 'Cubur Joj', 'TERCERO B'),
	array('Heidy Leticia', 'Cubur Rajpop', 'TERCERO B'),
	array('Cindy Marleny', 'Imuchac  Xicon', 'TERCERO B'),
	array('Jonathan Levi', 'Imuchac Ajuchan', 'TERCERO B'),
	array('Mildred Roxana', 'Imuchac Lopez', 'TERCERO B'),
	array('Hamilton Rocael', 'Ixtamalic Lopez', 'TERCERO B'),
	array('Marvin Arnoldo', 'Ovalle Colchaj', 'TERCERO B'),
	array('Katerin Rocio', 'Patzan Quexel', 'TERCERO B'),
	array('Jhonatan Alexander', 'Quisque Choc', 'TERCERO B'),
	array('Karen Vanely', 'Quisquinay Quisque', 'TERCERO B'),
	array('Dulce Abigail', 'Reguan de la Cruz', 'TERCERO B'),
	array('Lilian Roxana', 'Romero Zuñiga', 'TERCERO B'),
	array('Katherin Francisca', 'Romero Zuñiga', 'TERCERO B'),
	array('Gelber Aroldo', 'Rucal Perez', 'TERCERO B'),
	array('Juan Jose', 'Solis Chile', 'TERCERO B'),
	array('Blanca Esmeralda', 'Solloy Chiquito', 'TERCERO B'),
	array('Sandra Maribel', 'Subuyuj Raczan', 'TERCERO B'),
	array('Mariela Dayan', 'Subuyuj Xicon', 'TERCERO B'),
	array('Astrid Aracely', 'Sula  Rucal', 'TERCERO B'),
	array('Mirna Leticia', 'Tecun Tay', 'TERCERO B'),
	array('Betsy Dafne Berenice', 'Tejaxun Mazate', 'TERCERO B'),
	array('Sandy Elizabeth', 'Tejaxun Rucal', 'TERCERO B'),
	array('Cindy Marleny', 'Xetey Acual', 'TERCERO B'),
	array('Bryan Omar', 'Xetey Boch', 'TERCERO B'),
	array('Floridalma', 'Xetey Quisquinay', 'TERCERO B'),
	array('Kevin Rocael', 'Xicon Felipe', 'TERCERO B'),
	array('Esmirna Noemi', 'Yancis Gil', 'TERCERO B'),
	array('Erwan Alexander', 'Aguilar Solis', 'TERCERO C'),
	array('Jefry Alexander ', 'Cajbon Chiquito', 'TERCERO C'),
	array('Marelyn Bertalina', 'Cajbon Sula', 'TERCERO C'),
	array('Jenifer Aracely', 'Chiquito Joj', 'TERCERO C'),
	array('Brenda Mayari', 'Chis Cubur', 'TERCERO C'),
	array('Juan Jose', 'Chunchun Burrion', 'TERCERO C'),
	array('Cristel Vanessa', 'Cubur Cubur', 'TERCERO C'),
	array('Katerin Nayely', 'Curruchich Lopez', 'TERCERO C'),
	array('Luz Maria', 'Gil Gil', 'TERCERO C'),
	array('Kevin Orlando ', 'Gil Lopez', 'TERCERO C'),
	array('Cristian David', 'Gil Solis', 'TERCERO C'),
	array('Maria Magaly', 'Gil Sula', 'TERCERO C'),
	array('Carlos Eduardo ', 'Guamuch Solis', 'TERCERO C'),
	array('Maritza Fabiola', 'Ixtamalic Roquel', 'TERCERO C'),
	array('Doris Veraly Marina ', 'Joj Ajvix', 'TERCERO C'),
	array('Yonatan Eduardo', 'Joj Chivalan', 'TERCERO C'),
	array('Gerber Alexander', 'Joj Solis', 'TERCERO C'),
	array('Angel Augusto', 'Lima Stup', 'TERCERO C'),
	array('Marcos Antonio', 'Perez Xec', 'TERCERO C'),
	array('Hamilton Fernando', 'Quexel Chivalan', 'TERCERO C'),
	array('Lesly Paola', 'Quexel Gallina', 'TERCERO C'),
	array('Marvin Alexander', 'Quexel Solis', 'TERCERO C'),
	array('Heidy Susana', 'Quisque Reguan', 'TERCERO C'),
	array('Rocio Esmeralda', 'Reyes Us', 'TERCERO C'),
	array('Kimberly Marisol', 'Rucal Boj', 'TERCERO C'),
	array('Katerin Jazmin', 'Solis Chiquito', 'TERCERO C'),
	array('Lesly Aracely', 'Solis Felipe', 'TERCERO C'),
	array('Angel David', 'Sula Chocon', 'TERCERO C'),
	array('Jessica Carolina', 'Tajin Yancis', 'TERCERO C'),
	array('Rebeca Noemi', 'Tejaxun Sec', 'TERCERO C'),
	array('Jeymi Azucely', 'Texen Gallina', 'TERCERO C'),
	array('Bryan Emilio', 'Tomas Tejexun', 'TERCERO C'),
	array('Randall MIjail', 'Turuy Martinez', 'TERCERO C'),
	array('Kevin Alejandro', 'Turuy Tecen', 'TERCERO C'),
	array('Yol Diaz', 'Dana Noemi', 'TERCERO C'),
	array('Paola Esmeralda', 'Yol Diaz', 'TERCERO C'),
	array('Rudy Rolando', 'Ajuchan Chocon', 'TERCERO D'),
	array('Mayerly Azucena', 'Ajuchan Culajay', 'TERCERO D'),
	array('Levi Misael', 'Alquijay Jil', 'TERCERO D'),
	array('Cesar Jose Geovany', 'Burrion Ajuchan', 'TERCERO D'),
	array('Heydi Esperanza', 'Burrion Xicon', 'TERCERO D'),
	array('Marco Tulio', 'Chamale', 'TERCERO D'),
	array('Marcos Estuardo', 'Chile Cajbon', 'TERCERO D'),
	array('Luis Armando', 'Chiquito Choreque', 'TERCERO D'),
	array('Madelyn Jazmin', 'Chunchun Gil', 'TERCERO D'),
	array('Gerson Geovany', 'Cubur Acual', 'TERCERO D'),
	array('Lily Valeria', 'Gil Tejaxun', 'TERCERO D'),
	array('Dany Geovany', 'Guarchaj Subuyuj', 'TERCERO D'),
	array('Josselyn Marisol', 'Jutzuy Chon', 'TERCERO D'),
	array('Fredy Alexander', 'Laroj Chiquito', 'TERCERO D'),
	array('Jennifer Cristina', 'Orozco Quinac', 'TERCERO D'),
	array('Evelyn Lucrecia', 'Ortiz Rucal', 'TERCERO D'),
	array('Merlin', 'Puluc Raxzan', 'TERCERO D'),
	array('Hugo Estuardo', 'Quexel Solloy', 'TERCERO D'),
	array('Cindy Aracely', 'Quisquinay Xicon', 'TERCERO D'),
	array('Brandon Josue', 'Raczan Choreque', 'TERCERO D'),
	array('Fredy Alexander', 'Raczan Flores', 'TERCERO D'),
	array('Marco Antonio', 'Rajpop Chiquito', 'TERCERO D'),
	array('Josselyn Andrea', 'Reguan Chiquito', 'TERCERO D'),
	array('Jackeline Marleny', 'Sul Imuchac', 'TERCERO D'),
	array('Julisa Fernanda', 'Sula Coy', 'TERCERO D'),
	array('David Eduardo', 'Sula Rajpop ', 'TERCERO D'),
	array('Cindy Sucely', 'Sula Sula', 'TERCERO D'),
	array('Gerber Eduardo', 'Telon Ajuchan', 'TERCERO D'),
	array('Anderson David', 'Tomas Acual', 'TERCERO D'),
	array('Wendy Waleska', 'Tomas Cajbon', 'TERCERO D'),
	array('Joselin Marleny', 'Tomas Felipe', 'TERCERO D'),
	array('Maria Guadalupe', 'Turuy Choreque', 'TERCERO D'),
	array('Christian Geovani', 'Xetey Chaclan', 'TERCERO D'),
	array('Jairo Isaac', 'Yancis Lopez', 'TERCERO D'),
	array('Dayrin Amelia', 'Yol Alquijay', 'TERCERO D'),
	array('Allan Anthony', 'Ajuchan Popol', 'SEXTO A'),
	array('Gabriela Noemi', 'Asturias Ratzan', 'SEXTO A'),
	array('Brandon Eliu', 'Axpuac Chile', 'SEXTO A'),
	array('Jaqueline Johana', 'Barrios Bautista', 'SEXTO A'),
	array('Victor Miguel ', 'Barrios Bautista', 'SEXTO A'),
	array('Isabel Marleny', 'Cay Cubur', 'SEXTO A'),
	array('Miriam Yessenia', 'Cay Rucal', 'SEXTO A'),
	array('Oliver Alexander', 'Chiquito Quisquinay', 'SEXTO A'),
	array('Yeimy Beatriz ', 'Chiquito Tejaxun', 'SEXTO A'),
	array('Yesica Marleny', 'Chiroy Sula', 'SEXTO A'),
	array('Luis Enrique', 'Cubur Gil', 'SEXTO A'),
	array('Osvin Ismael', 'Culajay Maguey', 'SEXTO A'),
	array('Maria del Rosario', 'de leon Cubur', 'SEXTO A'),
	array('Claudia Leticia ', 'Ixtamalic Quexel', 'SEXTO A'),
	array('Linda Betsaida', 'Patzan Tejaxun', 'SEXTO A'),
	array('Jose Julian', 'Quic Chiquito', 'SEXTO A'),
	array('Jheymi Elizabeth', 'Quinti Sula', 'SEXTO A'),
	array('Lesly Yessenia', 'Quisquinay Quisque', 'SEXTO A'),
	array('Kelly Paola', 'Reguan Cay', 'SEXTO A'),
	array('Elida Marleny', 'Rucal Reyes', 'SEXTO A'),
	array('Bryan Alejandro', 'Solis Solis', 'SEXTO A'),
	array('Sandra Elvira', 'Sula Raczan', 'SEXTO A'),
	array('Angela Raquel', 'Tejaxun Gil', 'SEXTO A'),
	array('Elida Rossemary', 'Tejaxun Mazate', 'SEXTO A'),
	array('Maria Eunice', 'Tejaxun Us', 'SEXTO A'),
	array('Norma Sucely', 'Turuy Tecen', 'SEXTO A'),
	array('Jaqueline Estefania', 'Xoc Yancis', 'SEXTO A'),
	array('Jasminne Eunyce', 'Xulu Chiquito', 'SEXTO A'),
	array('Mishell Jamilette', 'Xunic Lopez', 'SEXTO A'),
	array('Eddy Armando', 'Yol  Asturias', 'SEXTO A'),
	array('German Estuardo', 'Ajuchan Tajin', 'SEXTO B'),
	array('Dalia Mariana', 'Alquijay Jil', 'SEXTO B'),
	array('Julia Marleny', 'Burrion Garcia', 'SEXTO B'),
	array('Steven Alexander', 'Castro Rucal', 'SEXTO B'),
	array('Jackeline Estefani', 'Chex Sun', 'SEXTO B'),
	array('Estefanni Lisbeth', 'Chile Ramirez', 'SEXTO B'),
	array('Jessica Magaly', 'Chiquito Jeronimo', 'SEXTO B'),
	array('Jose Manuel', 'Chiquito Lopez', 'SEXTO B'),
	array('Henry Alexander', 'Choreque Alcor', 'SEXTO B'),
	array('Brandon Orlando', 'Flores Quisque', 'SEXTO B'),
	array('Sonia Yudith', 'Gil Argueta', 'SEXTO B'),
	array('Edgar Estuardo', 'Gil Guzman', 'SEXTO B'),
	array('Luz Izabel', 'Gil Gil', 'SEXTO B'),
	array('Carlos Daniel', 'Gil Laroj', 'SEXTO B'),
	array('Jennifer Amanda', 'Gil Solis', 'SEXTO B'),
	array('Wilson Orlando', 'Guevara Calel', 'SEXTO B'),
	array('Catherine Karina', 'Imuchac Tajin', 'SEXTO B'),
	array('Lady Karina', 'Imuchac Escobar', 'SEXTO B'),
	array('Karen Estefania', 'Jutzuy Cubur', 'SEXTO B'),
	array('Mauricio', 'Lopez Cubur', 'SEXTO B'),
	array('Rangel Adonay', 'Lopez Ramirez', 'SEXTO B'),
	array('Kelvin Jhoel', 'Machan Yol', 'SEXTO B'),
	array('Oswaldo Josue', 'Ovalle Oseida', 'SEXTO B'),
	array('Denis Alexander', 'Pepio Tobar', 'SEXTO B'),
	array('Katerin Mishell', 'Pepio Tobar', 'SEXTO B'),
	array('Hugo Estuardo', 'Quisquinay Cubur', 'SEXTO B'),
	array('Gerber Alexander', 'Rabay Chiquito', 'SEXTO B'),
	array('Luis Fernando', 'Solis Gil', 'SEXTO B'),
	array('Cindy Marisol', 'Taquiej Cajbon', 'SEXTO B'),
	array('Marlon Arnoldo', 'Xicon Solloy', 'SEXTO B'),
	array('Helder Abel', 'Yol Alquijay', 'SEXTO B'),
	array('Brandon', 'Ajau Boche', 'SEXTO C'),
	array('Dulce', 'Alcor Oseida ', 'SEXTO C'),
	array('Josselyn', 'Asturias Imucha', 'SEXTO C'),
	array('Silvia', 'Chiquito Cay', 'SEXTO C'),
	array('Michelle', 'Chiquito Chiquito', 'SEXTO C'),
	array('Josue', 'Chiquito Chis', 'SEXTO C'),
	array('Sandra', 'Chiquito Rucal', 'SEXTO C'),
	array('Evelyn', 'Cubur Hernandez', 'SEXTO C'),
	array('Eliezer', 'Cubur Rucal', 'SEXTO C'),
	array('Ana', 'Cuxe Felipe', 'SEXTO C'),
	array('Kenner', 'Gil Cay', 'SEXTO C'),
	array('Wendy', 'Gil Cubur', 'SEXTO C'),
	array('Francis Alejandra', 'Gil Monroy', 'SEXTO C'),
	array('Mario', 'Gil Rucal', 'SEXTO C'),
	array('Juana', 'Gil Sul', 'SEXTO C'),
	array('Zayan', 'Gil Tejaxun', 'SEXTO C'),
	array('Belsy', 'Hernandez Xetey', 'SEXTO C'),
	array('Madelyn', 'Imuchac Lopez', 'SEXTO C'),
	array('Levin', 'Ixtamalic Socoreq', 'SEXTO C'),
	array('Selvin', 'Ixtamalic Socoreq', 'SEXTO C'),
	array('Carlos', 'Lima Stup', 'SEXTO C'),
	array('Mercy', 'Lopez Castañeda', 'SEXTO C'),
	array('Fernando', 'Orozco Morales', 'SEXTO C'),
	array('Wilson', 'Ovalle Oseida', 'SEXTO C'),
	array('Josue Daniel', 'Perez Ajqui', 'SEXTO C'),
	array('Luis Alfredo', 'Quisquinay Chiroy', 'SEXTO C'),
	array('Mirna', 'Romero Zuñiga', 'SEXTO C'),
	array('Brenda', 'Rucal Choreque', 'SEXTO C'),
	array('Alngela', 'Tejaxun Chiquito', 'SEXTO C'),
	array('Brenda', 'Tomas Cajbon', 'SEXTO C'),
	array('Andy', 'Tomas Tejaxun', 'SEXTO C'),
	array('Evelyn', 'Zamora Portelo', 'SEXTO C'),
	array('Glendy', 'Alquijay Morales', 'SEXTO D'),
	array('Ever', 'Asturias Morales', 'SEXTO D'),
	array('Marlen', 'Asturias Soliz', 'SEXTO D'),
	array('Patricia', 'Barrios Tobar', 'SEXTO D'),
	array('Nery', 'Burrios Xicon', 'SEXTO D'),
	array('Pedro', 'Camey Jeronimo', 'SEXTO D'),
	array('Kevin ', 'Chiquito Burrion', 'SEXTO D'),
	array('Vivian', 'Chiquito Chiquito', 'SEXTO D'),
	array('Maydelin', 'Chiquito Tubac', 'SEXTO D'),
	array('Yesmi', 'Choreque Felipe', 'SEXTO D'),
	array('Elder', 'Cubur Tejaxun', 'SEXTO D'),
	array('Rosario', 'Cuxulic Xoquic', 'SEXTO D'),
	array('Lesli Marlelny', 'España Sisimit', 'SEXTO D'),
	array('Hilda', 'Gil Joj', 'SEXTO D'),
	array('Darlyn', 'Gomez Tax', 'SEXTO D'),
	array('Luis', 'Jeronimo Quisquinay', 'SEXTO D'),
	array('Victor', 'Joj Luis', 'SEXTO D'),
	array('Francis Stefany', 'Laroj Chis', 'SEXTO D'),
	array('Josue', 'Mazate Laroj', 'SEXTO D'),
	array('Norma', 'Ojer Chiquito', 'SEXTO D'),
	array('Gerber', 'Ojer Laroj', 'SEXTO D'),
	array('Rudy', 'Paredes Cubur', 'SEXTO D'),
	array('Allyson Mayeli', 'Paztan Saloj', 'SEXTO D'),
	array('David', 'Perez Augustin', 'SEXTO D'),
	array('Katherine Rossemary', 'Quisque Chile', 'SEXTO D'),
	array('Brenda', 'Quisquinay Sula', 'SEXTO D'),
	array('Josselyn', 'Quisquinay Sula', 'SEXTO D'),
	array('Angel', 'Rabay Tapaz', 'SEXTO D'),
	array('Jessenya', 'Rucal Alcor', 'SEXTO D'),
	array('Jackeline', 'Rucal gallina', 'SEXTO D'),
	array('Henry', 'Xicon Felipe', 'SEXTO D')
);


class Clase
{
	private $intento;
	private $modalidad;
	private $nombres_fix;
	private $nombres_in;

	function __construct($arr_nombres, $modalidad = '')
	{
		$this->modalidad = $modalidad;
		$this->intento = 0;
		$this->nombres_fix = array();
		$this->nombres_in = $arr_nombres;
	}

	public function generar_nombre($nombre, $apellido, $limite=1)
	{
		$username = $this->modalidad;
		$username .= substr($nombre, 0, $limite).explode(' ', $apellido)[0];
		if(in_array($username, $this->nombres_fix)){
			$username = $this->generar_nombre($nombre, $apellido, $limite+1);
		}
		return $username;
	}

	public function recorrer_array()
	{
		foreach ($this->nombres_in as $key => $alumno) {
			$nombre = strtolower($alumno[0]);
			$apellido = strtolower($alumno[1]);
			$username = $this->generar_nombre($nombre, $apellido);

			array_push($this->nombres_fix, $username);
		}
	}

	public function ver_usuarios()
	{
		return $this->nombres_fix;
	}

	public function imprimir()
	{
		$text = '';
		$clase = $this->nombres_in[0][2];
		foreach ($this->nombres_fix as $numero => $value) {
			$clase_actual = $this->nombres_in[$numero][2];
			$clave = (!($clase == $clase_actual)) ? 1 : $clave+1;
			
			$text .= (!($clase == $clase_actual)) ? '<tr><td><hr></td></tr>' : '';
			$text .= '<tr><td>'.$clave.' ('.($numero+1).')</td><td>'.$value.'</td><td>'.$this->nombres_in[$numero][0].'</td><td>'.$this->nombres_in[$numero][1].'</td><td>'.$this->nombres_in[$numero][2].'</td></tr>
			';
			$clase = $clase_actual;
		}
		return $text;
	}
}

$user = new Clase($arr_nombres, 'f415');
$user->recorrer_array();
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>
	<table>
		<?php echo $user->imprimir(); ?>
	</table>
</body>
</html>