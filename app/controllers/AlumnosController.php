<?php

class AlumnosController extends BaseController {

    public function getActivos() {
        $alumnos = Alumno::activos();
        return Response::json($alumnos, 200);
    }

    public function getAll() {
        $alumnos = Persona::join('alumno', 'alumno.idpersonas', '=', 'persona.idpersonas')
                        ->join('curso', 'curso.idcurso', '=', 'alumno.idcurso')
                        ->join('plan_estudios', 'plan_estudios.idplan_estudios', '=', 'alumno.idplan_estudios')
			->join('niveles_academicos', 'curso.nivel', '=', 'niveles_academicos.idnivel')
                        ->leftJoin('grupo_academico_alumno', 'grupo_academico_alumno.nocuenta', '=', 'alumno.nocuenta')
                        ->leftJoin('grupo_academico', 'grupo_academico.idgrupo_academico', '=', 'grupo_academico_alumno.idgrupo_academico')
                        ->leftJoin('periodo', 'periodo.idperiodo', '=', 'grupo_academico.idperiodo')
			->Where('persona.alumno','=','1')
			->select(
                                array(
                                    //INFORMACIÓN ALUMNO
                                    DB::raw('Distinct(alumno.nocuenta) as matricula'),
                                    'alumno.estatus as estatus_admin',
                                    //INFORMACIÓN PERSONAL ALUMNO
                                    'persona.idpersonas as idpersonas',
                                    'persona.nombre as nom',
                                    'persona.apellidopat as appat',
                                    'persona.apellidomat as apmat',
                                    'persona.curp as curp',
                                    'persona.fechanaci as fecha_nac',
                                    //INFORMACIÓN CARRERA/PLAN_ESTUDIOS ALUMNO
                                    'curso.nombre as carrera',
                                    'curso.nombre_corto as nc',
                                    'plan_estudios.titulo as pe',
                                    //ÚLTIMO PERIODO CURSADO
                                    'periodo.idperiodo as idperiodo',
                                    //ÚLTIMO GRUPO ACADÉMICO
                                    'grupo_academico.grado',
                                    'grupo_academico.grupo',
                                    'grupo_academico.clave',
				    'curso.nivel',
				    'niveles_academicos.nombre as nombre_nivel'
                                )
                        )
			->distinct()
			->get();
        return Response::json($alumnos, 200);
    }

    public function getAllPersona() {
        $alumnos = Persona::join('alumno', 'alumno.idpersonas', '=', 'persona.idpersonas')
                        ->join('curso', 'curso.idcurso', '=', 'alumno.idcurso')
                        ->join('plan_estudios', 'plan_estudios.idplan_estudios', '=', 'alumno.idplan_estudios')
			->join('niveles_academicos', 'curso.nivel', '=', 'niveles_academicos.idnivel')
                        ->leftJoin('grupo_academico_alumno', 'grupo_academico_alumno.nocuenta', '=', 'alumno.nocuenta')
                        ->leftJoin('grupo_academico', 'grupo_academico.idgrupo_academico', '=', 'grupo_academico_alumno.idgrupo_academico')
                        ->leftJoin('periodo', 'periodo.idperiodo', '=', 'grupo_academico.idperiodo')
			->Where('persona.alumno','=','1')
			->select(
                                array(
                                    //INFORMACIÓN ALUMNO
                                    'alumno.nocuenta as matricula',
                                    'alumno.estatus as estatus_admin',
                                    //INFORMACIÓN PERSONAL ALUMNO
                                    'persona.idpersonas as idpersonas',
                                    'persona.nombre as nom',
                                    'persona.apellidopat as appat',
                                    'persona.apellidomat as apmat',
                                    'persona.curp as curp',
                                    'persona.fechanaci as fecha_nac',
                                    //INFORMACIÓN CARRERA/PLAN_ESTUDIOS ALUMNO
                                    'curso.nombre as carrera',
                                    'curso.nombre_corto as nc',
                                    'plan_estudios.titulo as pe',
                                    //ÚLTIMO PERIODO CURSADO
                                    'periodo.idperiodo as idperiodo',
                                    //ÚLTIMO GRUPO ACADÉMICO
                                    'grupo_academico.grado',
                                    'grupo_academico.grupo',
                                    'grupo_academico.clave',
				    'curso.nivel',
				    'niveles_academicos.nombre as nombre_nivel'
                                )
                        )
			->get();
//	$alumnos= DB::table('niveles_academicos')->Select('*')->limit(10)->get();
        return Response::json($alumnos, 200);
    }
    
    public function getIdPersona() {
        $alumnos = Persona::join('alumno', 'alumno.idpersonas', '=', 'persona.idpersonas')
                        ->join('curso', 'curso.idcurso', '=', 'alumno.idcurso')
                        ->join('plan_estudios', 'plan_estudios.idplan_estudios', '=', 'alumno.idplan_estudios')
			->join('niveles_academicos', 'curso.nivel', '=', 'niveles_academicos.idnivel')
                        ->leftJoin('grupo_academico_alumno', 'grupo_academico_alumno.nocuenta', '=', 'alumno.nocuenta')
                        ->leftJoin('grupo_academico', 'grupo_academico.idgrupo_academico', '=', 'grupo_academico_alumno.idgrupo_academico')
                        ->leftJoin('periodo', 'periodo.idperiodo', '=', 'grupo_academico.idperiodo')
			->Where('persona.alumno','=','1')
			->select(
                                array(
                                    //INFORMACIÓN ALUMNO
                                    'alumno.nocuenta as matricula',
                                    'persona.idpersonas as idpersonas',
                                    //'persona.nombre as nom',
                                    //'persona.apellidopat as appat',
                                    //'persona.apellidomat as apmat',
                                    //INFORMACIÓN CARRERA/PLAN_ESTUDIOS ALUMNO
                                    //'curso.nombre as carrera',
                                    //ÚLTIMO PERIODO CURSADO
                                    //'periodo.idperiodo as idperiodo',
                                    //ÚLTIMO GRUPO ACADÉMICO
                                    //'grupo_academico.grado',
                                    //'grupo_academico.grupo',
				    //'curso.nivel',
				    //'niveles_academicos.nombre as nombre_nivel'
                                )
                        )
			->get();
//	$alumnos= DB::table('niveles_academicos')->Select('*')->limit(10)->get();
        return Response::json($alumnos, 200);
    }
}
