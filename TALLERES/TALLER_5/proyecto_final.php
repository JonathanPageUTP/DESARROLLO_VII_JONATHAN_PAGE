<?php
/**
 * Proyecto Final - Sistema de Gestión de Estudiantes
 * TALLER_5/proyecto_final.php
 * 
 * Sistema completo para gestionar información de estudiantes con funcionalidades
 * avanzadas de búsqueda, filtrado y generación de reportes.
 */

// ============================================================================
// CLASE ESTUDIANTE
// ============================================================================

class Estudiante {
    private int $id;
    private string $nombre;
    private int $edad;
    private string $carrera;
    private array $materias; // Arreglo asociativo: ['materia' => calificacion]
    private array $flags; // Banderas de estado académico
    
    /**
     * Constructor que inicializa todos los atributos
     */
    public function __construct(int $id, string $nombre, int $edad, string $carrera) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->edad = $edad;
        $this->carrera = $carrera;
        $this->materias = [];
        $this->flags = [];
    }
    
    /**
     * Agrega una materia con su calificación
     */
    public function agregarMateria(string $materia, float $calificacion): void {
        if ($calificacion < 0 || $calificacion > 100) {
            throw new InvalidArgumentException("La calificación debe estar entre 0 y 100");
        }
        $this->materias[$materia] = $calificacion;
        $this->actualizarFlags();
    }
    
    /**
     * Calcula y retorna el promedio de calificaciones
     */
    public function obtenerPromedio(): float {
        if (empty($this->materias)) {
            return 0.0;
        }
        return array_sum($this->materias) / count($this->materias);
    }
    
    /**
     * Retorna un arreglo asociativo con toda la información del estudiante
     */
    public function obtenerDetalles(): array {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'edad' => $this->edad,
            'carrera' => $this->carrera,
            'materias' => $this->materias,
            'promedio' => $this->obtenerPromedio(),
            'flags' => $this->flags
        ];
    }
    
    /**
     * Actualiza las banderas del estudiante según su rendimiento
     */
    private function actualizarFlags(): void {
        $promedio = $this->obtenerPromedio();
        $this->flags = [];
        
        // Flag: Honor Roll (promedio >= 90)
        if ($promedio >= 90) {
            $this->flags[] = 'Honor Roll';
        }
        
        // Flag: En riesgo académico (promedio < 60)
        if ($promedio < 60 && $promedio > 0) {
            $this->flags[] = 'En Riesgo Académico';
        }
        
        // Flag: Materias reprobadas (calificación < 60)
        $materiasReprobadas = array_filter($this->materias, fn($cal) => $cal < 60);
        if (count($materiasReprobadas) > 0) {
            $this->flags[] = 'Tiene Materias Reprobadas (' . count($materiasReprobadas) . ')';
        }
        
        // Flag: Excelencia (todas las materias >= 85)
        if (!empty($this->materias) && min($this->materias) >= 85) {
            $this->flags[] = 'Excelencia Académica';
        }
    }
    
    /**
     * Método mágico para convertir el objeto a string
     */
    public function __toString(): string {
        $detalles = $this->obtenerDetalles();
        $output = "===========================================\n";
        $output .= "ID: {$detalles['id']} | {$detalles['nombre']}\n";
        $output .= "Edad: {$detalles['edad']} | Carrera: {$detalles['carrera']}\n";
        $output .= "Promedio: " . number_format($detalles['promedio'], 2) . "\n";
        
        if (!empty($detalles['flags'])) {
            $output .= "Flags: " . implode(', ', $detalles['flags']) . "\n";
        }
        
        $output .= "Materias:\n";
        foreach ($detalles['materias'] as $materia => $calificacion) {
            $output .= "  - {$materia}: {$calificacion}\n";
        }
        $output .= "===========================================\n";
        
        return $output;
    }
    
    // Getters
    public function getId(): int { return $this->id; }
    public function getNombre(): string { return $this->nombre; }
    public function getEdad(): int { return $this->edad; }
    public function getCarrera(): string { return $this->carrera; }
    public function getMaterias(): array { return $this->materias; }
    public function getFlags(): array { return $this->flags; }
}

// ============================================================================
// CLASE SISTEMA DE GESTIÓN DE ESTUDIANTES
// ============================================================================

class SistemaGestionEstudiantes {
    private array $estudiantes;
    private array $graduados;
    
    public function __construct() {
        $this->estudiantes = [];
        $this->graduados = [];
    }
    
    /**
     * Agrega un nuevo estudiante al sistema
     */
    public function agregarEstudiante(Estudiante $estudiante): void {
        $this->estudiantes[$estudiante->getId()] = $estudiante;
    }
    
    /**
     * Obtiene un estudiante por su ID
     */
    public function obtenerEstudiante(int $id): ?Estudiante {
        return $this->estudiantes[$id] ?? null;
    }
    
    /**
     * Retorna un arreglo con todos los estudiantes
     */
    public function listarEstudiantes(): array {
        return $this->estudiantes;
    }
    
    /**
     * Calcula el promedio general de todos los estudiantes
     */
    public function calcularPromedioGeneral(): float {
        if (empty($this->estudiantes)) {
            return 0.0;
        }
        
        // Usando array_reduce para sumar todos los promedios
        $sumaPromedios = array_reduce(
            $this->estudiantes,
            fn($carry, $estudiante) => $carry + $estudiante->obtenerPromedio(),
            0
        );
        
        return $sumaPromedios / count($this->estudiantes);
    }
    
    /**
     * Retorna estudiantes de una carrera específica
     */
    public function obtenerEstudiantesPorCarrera(string $carrera): array {
        // Usando array_filter para filtrar por carrera
        return array_filter(
            $this->estudiantes,
            fn($estudiante) => strcasecmp($estudiante->getCarrera(), $carrera) === 0
        );
    }
    
    /**
     * Retorna el estudiante con el promedio más alto
     */
    public function obtenerMejorEstudiante(): ?Estudiante {
        if (empty($this->estudiantes)) {
            return null;
        }
        
        // Usando array_reduce para encontrar el mejor estudiante
        return array_reduce(
            $this->estudiantes,
            function($mejor, $actual) {
                if ($mejor === null) return $actual;
                return $actual->obtenerPromedio() > $mejor->obtenerPromedio() ? $actual : $mejor;
            },
            null
        );
    }
    
    /**
     * Genera un reporte de rendimiento por materia
     */
    public function generarReporteRendimiento(): array {
        $reportePorMateria = [];
        
        // Recopilar todas las calificaciones por materia
        foreach ($this->estudiantes as $estudiante) {
            foreach ($estudiante->getMaterias() as $materia => $calificacion) {
                if (!isset($reportePorMateria[$materia])) {
                    $reportePorMateria[$materia] = [];
                }
                $reportePorMateria[$materia][] = $calificacion;
            }
        }
        
        // Calcular estadísticas por materia usando array_map
        $reporte = array_map(function($calificaciones) {
            return [
                'promedio' => array_sum($calificaciones) / count($calificaciones),
                'calificacion_maxima' => max($calificaciones),
                'calificacion_minima' => min($calificaciones),
                'total_estudiantes' => count($calificaciones)
            ];
        }, $reportePorMateria);
        
        return $reporte;
    }
    
    /**
     * Gradúa a un estudiante, moviéndolo al arreglo de graduados
     */
    public function graduarEstudiante(int $id): bool {
        if (!isset($this->estudiantes[$id])) {
            return false;
        }
        
        $estudiante = $this->estudiantes[$id];
        $this->graduados[$id] = $estudiante;
        unset($this->estudiantes[$id]);
        
        return true;
    }
    
    /**
     * Genera un ranking de estudiantes ordenados por promedio
     */
    public function generarRanking(): array {
        $ranking = $this->estudiantes;
        
        // Ordenar por promedio descendente
        usort($ranking, function($a, $b) {
            return $b->obtenerPromedio() <=> $a->obtenerPromedio();
        });
        
        // Agregar posición al ranking
        return array_map(function($index, $estudiante) {
            return [
                'posicion' => $index + 1,
                'estudiante' => $estudiante,
                'promedio' => $estudiante->obtenerPromedio()
            ];
        }, array_keys($ranking), $ranking);
    }
    
    /**
     * Busca estudiantes por nombre o carrera (búsqueda parcial e insensible a mayúsculas)
     */
    public function buscarEstudiantes(string $termino): array {
        $terminoLower = mb_strtolower($termino);
        
        return array_filter($this->estudiantes, function($estudiante) use ($terminoLower) {
            $nombreLower = mb_strtolower($estudiante->getNombre());
            $carreraLower = mb_strtolower($estudiante->getCarrera());
            
            return strpos($nombreLower, $terminoLower) !== false || 
                   strpos($carreraLower, $terminoLower) !== false;
        });
    }
    
    /**
     * Genera estadísticas por carrera
     */
    public function generarEstadisticasPorCarrera(): array {
        $estadisticas = [];
        
        // Agrupar estudiantes por carrera
        foreach ($this->estudiantes as $estudiante) {
            $carrera = $estudiante->getCarrera();
            
            if (!isset($estadisticas[$carrera])) {
                $estadisticas[$carrera] = [
                    'estudiantes' => [],
                    'numero_estudiantes' => 0,
                    'promedio_general' => 0,
                    'mejor_estudiante' => null
                ];
            }
            
            $estadisticas[$carrera]['estudiantes'][] = $estudiante;
        }
        
        // Calcular estadísticas para cada carrera
        foreach ($estadisticas as $carrera => &$datos) {
            $datos['numero_estudiantes'] = count($datos['estudiantes']);
            
            // Calcular promedio general de la carrera
            $sumaPromedios = array_reduce(
                $datos['estudiantes'],
                fn($carry, $est) => $carry + $est->obtenerPromedio(),
                0
            );
            $datos['promedio_general'] = $sumaPromedios / $datos['numero_estudiantes'];
            
            // Encontrar mejor estudiante de la carrera
            $datos['mejor_estudiante'] = array_reduce(
                $datos['estudiantes'],
                function($mejor, $actual) {
                    if ($mejor === null) return $actual;
                    return $actual->obtenerPromedio() > $mejor->obtenerPromedio() ? $actual : $mejor;
                },
                null
            );
            
            // Remover el arreglo de estudiantes del resultado final
            unset($datos['estudiantes']);
        }
        
        return $estadisticas;
    }
    
    /**
     * Obtiene estudiantes por flag específico
     */
    public function obtenerEstudiantesPorFlag(string $flag): array {
        return array_filter($this->estudiantes, function($estudiante) use ($flag) {
            return in_array($flag, $estudiante->getFlags());
        });
    }
    
    /**
     * Obtiene la lista de graduados
     */
    public function obtenerGraduados(): array {
        return $this->graduados;
    }
}

// ============================================================================
// SECCIÓN DE PRUEBAS
// ============================================================================

echo "╔══════════════════════════════════════════════════════════════════╗\n";
echo "║   SISTEMA DE GESTIÓN DE ESTUDIANTES - PRUEBAS COMPLETAS         ║\n";
echo "╚══════════════════════════════════════════════════════════════════╝\n\n";

// Instanciar el sistema
$sistema = new SistemaGestionEstudiantes();

// Crear 10 estudiantes con diferentes carreras y calificaciones
echo "📝 CREANDO ESTUDIANTES...\n\n";

$estudiante1 = new Estudiante(1, "Ana García", 20, "Ingeniería de Sistemas");
$estudiante1->agregarMateria("Programación", 95);
$estudiante1->agregarMateria("Matemáticas", 92);
$estudiante1->agregarMateria("Base de Datos", 98);
$sistema->agregarEstudiante($estudiante1);

$estudiante2 = new Estudiante(2, "Carlos Méndez", 22, "Medicina");
$estudiante2->agregarMateria("Anatomía", 88);
$estudiante2->agregarMateria("Fisiología", 85);
$estudiante2->agregarMateria("Química", 90);
$sistema->agregarEstudiante($estudiante2);

$estudiante3 = new Estudiante(3, "María López", 21, "Ingeniería de Sistemas");
$estudiante3->agregarMateria("Programación", 78);
$estudiante3->agregarMateria("Matemáticas", 82);
$estudiante3->agregarMateria("Base de Datos", 80);
$sistema->agregarEstudiante($estudiante3);

$estudiante4 = new Estudiante(4, "Juan Pérez", 19, "Derecho");
$estudiante4->agregarMateria("Derecho Civil", 55);
$estudiante4->agregarMateria("Derecho Penal", 58);
$estudiante4->agregarMateria("Constitucional", 62);
$sistema->agregarEstudiante($estudiante4);

$estudiante5 = new Estudiante(5, "Laura Rodríguez", 23, "Medicina");
$estudiante5->agregarMateria("Anatomía", 94);
$estudiante5->agregarMateria("Fisiología", 96);
$estudiante5->agregarMateria("Química", 93);
$sistema->agregarEstudiante($estudiante5);

$estudiante6 = new Estudiante(6, "Pedro Sánchez", 20, "Arquitectura");
$estudiante6->agregarMateria("Diseño", 87);
$estudiante6->agregarMateria("Construcción", 89);
$estudiante6->agregarMateria("Historia del Arte", 91);
$sistema->agregarEstudiante($estudiante6);

$estudiante7 = new Estudiante(7, "Sofia Martínez", 22, "Derecho");
$estudiante7->agregarMateria("Derecho Civil", 92);
$estudiante7->agregarMateria("Derecho Penal", 90);
$estudiante7->agregarMateria("Constitucional", 94);
$sistema->agregarEstudiante($estudiante7);

$estudiante8 = new Estudiante(8, "Diego Torres", 21, "Ingeniería de Sistemas");
$estudiante8->agregarMateria("Programación", 70);
$estudiante8->agregarMateria("Matemáticas", 65);
$estudiante8->agregarMateria("Base de Datos", 72);
$sistema->agregarEstudiante($estudiante8);

$estudiante9 = new Estudiante(9, "Valentina Cruz", 20, "Arquitectura");
$estudiante9->agregarMateria("Diseño", 96);
$estudiante9->agregarMateria("Construcción", 95);
$estudiante9->agregarMateria("Historia del Arte", 97);
$sistema->agregarEstudiante($estudiante9);

$estudiante10 = new Estudiante(10, "Andrés Gómez", 24, "Medicina");
$estudiante10->agregarMateria("Anatomía", 45);
$estudiante10->agregarMateria("Fisiología", 52);
$estudiante10->agregarMateria("Química", 48);
$sistema->agregarEstudiante($estudiante10);

echo "✅ 10 estudiantes creados exitosamente!\n\n";

// ============================================================================
// DEMOSTRACIÓN DE FUNCIONALIDADES
// ============================================================================

echo "══════════════════════════════════════════════════════════════════\n";
echo "1️⃣  LISTAR TODOS LOS ESTUDIANTES\n";
echo "══════════════════════════════════════════════════════════════════\n";
foreach ($sistema->listarEstudiantes() as $estudiante) {
    echo $estudiante;
}

echo "\n══════════════════════════════════════════════════════════════════\n";
echo "2️⃣  PROMEDIO GENERAL DEL SISTEMA\n";
echo "══════════════════════════════════════════════════════════════════\n";
echo "Promedio General: " . number_format($sistema->calcularPromedioGeneral(), 2) . "\n\n";

echo "══════════════════════════════════════════════════════════════════\n";
echo "3️⃣  MEJOR ESTUDIANTE\n";
echo "══════════════════════════════════════════════════════════════════\n";
$mejorEstudiante = $sistema->obtenerMejorEstudiante();
if ($mejorEstudiante) {
    echo "🏆 Mejor Estudiante: {$mejorEstudiante->getNombre()}\n";
    echo "   Promedio: " . number_format($mejorEstudiante->obtenerPromedio(), 2) . "\n";
    echo "   Carrera: {$mejorEstudiante->getCarrera()}\n\n";
}

echo "══════════════════════════════════════════════════════════════════\n";
echo "4️⃣  ESTUDIANTES POR CARRERA (Ingeniería de Sistemas)\n";
echo "══════════════════════════════════════════════════════════════════\n";
$ingenierosISistemas = $sistema->obtenerEstudiantesPorCarrera("Ingeniería de Sistemas");
foreach ($ingenierosISistemas as $estudiante) {
    echo "- {$estudiante->getNombre()} (Promedio: " . 
         number_format($estudiante->obtenerPromedio(), 2) . ")\n";
}
echo "\n";

echo "══════════════════════════════════════════════════════════════════\n";
echo "5️⃣  RANKING DE ESTUDIANTES\n";
echo "══════════════════════════════════════════════════════════════════\n";
$ranking = $sistema->generarRanking();
foreach ($ranking as $posicion) {
    echo "#{$posicion['posicion']} - {$posicion['estudiante']->getNombre()} ";
    echo "({$posicion['estudiante']->getCarrera()}) - ";
    echo "Promedio: " . number_format($posicion['promedio'], 2) . "\n";
}
echo "\n";

echo "══════════════════════════════════════════════════════════════════\n";
echo "6️⃣  REPORTE DE RENDIMIENTO POR MATERIA\n";
echo "══════════════════════════════════════════════════════════════════\n";
$reporteRendimiento = $sistema->generarReporteRendimiento();
foreach ($reporteRendimiento as $materia => $stats) {
    echo "📚 {$materia}:\n";
    echo "   Promedio: " . number_format($stats['promedio'], 2) . "\n";
    echo "   Calificación Máxima: {$stats['calificacion_maxima']}\n";
    echo "   Calificación Mínima: {$stats['calificacion_minima']}\n";
    echo "   Total Estudiantes: {$stats['total_estudiantes']}\n\n";
}

echo "══════════════════════════════════════════════════════════════════\n";
echo "7️⃣  BÚSQUEDA DE ESTUDIANTES (término: 'mar')\n";
echo "══════════════════════════════════════════════════════════════════\n";
$resultadosBusqueda = $sistema->buscarEstudiantes("mar");
foreach ($resultadosBusqueda as $estudiante) {
    echo "🔍 {$estudiante->getNombre()} - {$estudiante->getCarrera()}\n";
}
echo "\n";

echo "══════════════════════════════════════════════════════════════════\n";
echo "8️⃣  ESTADÍSTICAS POR CARRERA\n";
echo "══════════════════════════════════════════════════════════════════\n";
$estadisticasCarreras = $sistema->generarEstadisticasPorCarrera();
foreach ($estadisticasCarreras as $carrera => $stats) {
    echo "🎓 {$carrera}:\n";
    echo "   Número de Estudiantes: {$stats['numero_estudiantes']}\n";
    echo "   Promedio General: " . number_format($stats['promedio_general'], 2) . "\n";
    echo "   Mejor Estudiante: {$stats['mejor_estudiante']->getNombre()} ";
    echo "(Promedio: " . number_format($stats['mejor_estudiante']->obtenerPromedio(), 2) . ")\n\n";
}

echo "══════════════════════════════════════════════════════════════════\n";
echo "9️⃣  ESTUDIANTES POR FLAG\n";
echo "══════════════════════════════════════════════════════════════════\n";

echo "🏅 Honor Roll:\n";
$honorRoll = $sistema->obtenerEstudiantesPorFlag("Honor Roll");
foreach ($honorRoll as $estudiante) {
    echo "   - {$estudiante->getNombre()} (Promedio: " . 
         number_format($estudiante->obtenerPromedio(), 2) . ")\n";
}

echo "\n⚠️  En Riesgo Académico:\n";
$enRiesgo = $sistema->obtenerEstudiantesPorFlag("En Riesgo Académico");
foreach ($enRiesgo as $estudiante) {
    echo "   - {$estudiante->getNombre()} (Promedio: " . 
         number_format($estudiante->obtenerPromedio(), 2) . ")\n";
}

echo "\n⭐ Excelencia Académica:\n";
$excelencia = $sistema->obtenerEstudiantesPorFlag("Excelencia Académica");
foreach ($excelencia as $estudiante) {
    echo "   - {$estudiante->getNombre()} (Promedio: " . 
         number_format($estudiante->obtenerPromedio(), 2) . ")\n";
}
echo "\n";

echo "══════════════════════════════════════════════════════════════════\n";
echo "🔟  GRADUACIÓN DE ESTUDIANTE\n";
echo "══════════════════════════════════════════════════════════════════\n";
echo "Graduando a Valentina Cruz (ID: 9)...\n";
if ($sistema->graduarEstudiante(9)) {
    echo "✅ Estudiante graduado exitosamente!\n";
    echo "Total de graduados: " . count($sistema->obtenerGraduados()) . "\n";
    echo "Estudiantes activos: " . count($sistema->listarEstudiantes()) . "\n";
} else {
    echo "❌ Error al graduar estudiante.\n";
}
echo "\n";

echo "══════════════════════════════════════════════════════════════════\n";
echo "1️⃣1️⃣  OBTENER ESTUDIANTE POR ID\n";
echo "══════════════════════════════════════════════════════════════════\n";
$estudianteBuscado = $sistema->obtenerEstudiante(5);
if ($estudianteBuscado) {
    echo $estudianteBuscado;
} else {
    echo "❌ Estudiante no encontrado.\n";
}

echo "══════════════════════════════════════════════════════════════════\n";
echo "1️⃣2️⃣  MANEJO DE ERRORES\n";
echo "══════════════════════════════════════════════════════════════════\n";
echo "Intentando buscar estudiante con ID inexistente (999)...\n";
$estudianteInexistente = $sistema->obtenerEstudiante(999);
if ($estudianteInexistente === null) {
    echo "✅ Manejo correcto: Estudiante no encontrado.\n";
}

echo "\nIntentando agregar calificación inválida...\n";
try {
    $estudiante1->agregarMateria("Prueba", 150); // Calificación inválida
} catch (InvalidArgumentException $e) {
    echo "✅ Manejo correcto: {$e->getMessage()}\n";
}
echo "\n";

echo "╔══════════════════════════════════════════════════════════════════╗\n";
echo "║              ✅ TODAS LAS PRUEBAS COMPLETADAS                    ║\n";
echo "╚══════════════════════════════════════════════════════════════════╝\n";

?>