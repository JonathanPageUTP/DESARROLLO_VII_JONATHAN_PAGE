# 📚 Sistema de Gestión de Estudiantes

Sistema completo de gestión académica desarrollado en PHP para administrar información de estudiantes, calificaciones y generar reportes estadísticos.

## 📋 Tabla de Contenidos

- [Características](#características)
- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Uso Básico](#uso-básico)
- [Funcionalidades](#funcionalidades)
- [Ejemplos de Código](#ejemplos-de-código)
- [Estructura del Proyecto](#estructura-del-proyecto)

---

## ✨ Características

- **Gestión completa de estudiantes**: Crear, buscar, listar y graduar estudiantes
- **Sistema de calificaciones**: Agregar materias con sus calificaciones
- **Reportes avanzados**: Estadísticas por carrera, ranking y rendimiento por materia
- **Sistema de flags**: Identificación automática de estudiantes en honor roll, riesgo académico, etc.
- **Búsqueda inteligente**: Búsquedas parciales e insensibles a mayúsculas
- **Validación de datos**: Manejo robusto de errores y validación de calificaciones

---

## 💻 Requisitos

- PHP 7.4 o superior
- Extensión `mbstring` habilitada (para manejo de strings Unicode)

---

## 🚀 Instalación

1. Clona o descarga el proyecto en tu carpeta `TALLER_5`
2. Asegúrate de tener PHP instalado en tu sistema
3. Ejecuta el archivo desde la terminal:

```bash
php proyecto_final.php
```

---

## 📖 Uso Básico

### Crear el Sistema

```php
// Instanciar el sistema de gestión
$sistema = new SistemaGestionEstudiantes();
```

### Crear un Estudiante

```php
// Crear un nuevo estudiante
$estudiante = new Estudiante(1, "Ana García", 20, "Ingeniería de Sistemas");

// Agregar materias con calificaciones
$estudiante->agregarMateria("Programación", 95);
$estudiante->agregarMateria("Matemáticas", 92);
$estudiante->agregarMateria("Base de Datos", 98);

// Agregar al sistema
$sistema->agregarEstudiante($estudiante);
```

### Consultar Información

```php
// Obtener promedio del estudiante
$promedio = $estudiante->obtenerPromedio();

// Obtener detalles completos
$detalles = $estudiante->obtenerDetalles();

// Imprimir información (usa __toString)
echo $estudiante;
```

---

## 🎯 Funcionalidades

### Clase Estudiante

| Método | Descripción | Retorno |
|--------|-------------|---------|
| `agregarMateria($materia, $calificacion)` | Agrega una materia con su calificación | `void` |
| `obtenerPromedio()` | Calcula el promedio de calificaciones | `float` |
| `obtenerDetalles()` | Retorna toda la información del estudiante | `array` |
| `getId()`, `getNombre()`, etc. | Getters para atributos | Tipo específico |

**Flags automáticos generados:**
- 🏅 **Honor Roll**: Promedio ≥ 90
- ⚠️ **En Riesgo Académico**: Promedio < 60
- ❌ **Materias Reprobadas**: Calificación < 60 en alguna materia
- ⭐ **Excelencia Académica**: Todas las materias ≥ 85

### Clase SistemaGestionEstudiantes

#### Gestión Básica

| Método | Descripción | Retorno |
|--------|-------------|---------|
| `agregarEstudiante($estudiante)` | Agrega un estudiante al sistema | `void` |
| `obtenerEstudiante($id)` | Busca un estudiante por ID | `Estudiante\|null` |
| `listarEstudiantes()` | Lista todos los estudiantes activos | `array` |
| `graduarEstudiante($id)` | Gradúa y mueve un estudiante | `bool` |
| `obtenerGraduados()` | Lista estudiantes graduados | `array` |

#### Análisis y Reportes

| Método | Descripción | Retorno |
|--------|-------------|---------|
| `calcularPromedioGeneral()` | Promedio de todos los estudiantes | `float` |
| `obtenerMejorEstudiante()` | Estudiante con mejor promedio | `Estudiante\|null` |
| `generarRanking()` | Ranking ordenado por promedio | `array` |
| `generarReporteRendimiento()` | Estadísticas por materia | `array` |
| `generarEstadisticasPorCarrera()` | Estadísticas agrupadas por carrera | `array` |

#### Búsqueda y Filtrado

| Método | Descripción | Retorno |
|--------|-------------|---------|
| `obtenerEstudiantesPorCarrera($carrera)` | Filtra estudiantes por carrera | `array` |
| `buscarEstudiantes($termino)` | Búsqueda por nombre o carrera | `array` |
| `obtenerEstudiantesPorFlag($flag)` | Filtra por flag específico | `array` |

---

## 💡 Ejemplos de Código

### Ejemplo 1: Crear y Gestionar Estudiantes

```php
// Crear el sistema
$sistema = new SistemaGestionEstudiantes();

// Crear estudiante
$estudiante1 = new Estudiante(1, "María López", 21, "Medicina");
$estudiante1->agregarMateria("Anatomía", 95);
$estudiante1->agregarMateria("Fisiología", 92);

// Agregar al sistema
$sistema->agregarEstudiante($estudiante1);

// Ver información
echo $estudiante1; // Usa __toString
```

### Ejemplo 2: Generar Reportes

```php
// Reporte de rendimiento por materia
$reporte = $sistema->generarReporteRendimiento();
foreach ($reporte as $materia => $stats) {
    echo "Materia: {$materia}\n";
    echo "Promedio: {$stats['promedio']}\n";
    echo "Máxima: {$stats['calificacion_maxima']}\n";
    echo "Mínima: {$stats['calificacion_minima']}\n\n";
}
```

### Ejemplo 3: Búsqueda Inteligente

```php
// Búsqueda parcial e insensible a mayúsculas
$resultados = $sistema->buscarEstudiantes("ing"); 
// Encuentra "Ingeniería", "Ingeniería de Sistemas", etc.

foreach ($resultados as $estudiante) {
    echo "{$estudiante->getNombre()} - {$estudiante->getCarrera()}\n";
}
```

### Ejemplo 4: Obtener Mejor Estudiante

```php
$mejor = $sistema->obtenerMejorEstudiante();
if ($mejor) {
    echo "🏆 Mejor Estudiante: {$mejor->getNombre()}\n";
    echo "Promedio: " . number_format($mejor->obtenerPromedio(), 2) . "\n";
}
```

### Ejemplo 5: Estadísticas por Carrera

```php
$estadisticas = $sistema->generarEstadisticasPorCarrera();

foreach ($estadisticas as $carrera => $datos) {
    echo "Carrera: {$carrera}\n";
    echo "Estudiantes: {$datos['numero_estudiantes']}\n";
    echo "Promedio: " . number_format($datos['promedio_general'], 2) . "\n";
    echo "Mejor: {$datos['mejor_estudiante']->getNombre()}\n\n";
}
```

### Ejemplo 6: Filtrar por Flags

```php
// Estudiantes en Honor Roll
$honorRoll = $sistema->obtenerEstudiantesPorFlag("Honor Roll");
echo "Estudiantes destacados: " . count($honorRoll) . "\n";

// Estudiantes en riesgo
$enRiesgo = $sistema->obtenerEstudiantesPorFlag("En Riesgo Académico");
echo "Estudiantes en riesgo: " . count($enRiesgo) . "\n";
```

### Ejemplo 7: Generar Ranking

```php
$ranking = $sistema->generarRanking();

foreach ($ranking as $posicion) {
    echo "#{$posicion['posicion']} - ";
    echo "{$posicion['estudiante']->getNombre()} - ";
    echo "Promedio: " . number_format($posicion['promedio'], 2) . "\n";
}
```

### Ejemplo 8: Graduar Estudiantes

```php
// Graduar un estudiante
if ($sistema->graduarEstudiante(5)) {
    echo "✅ Estudiante graduado exitosamente!\n";
}

// Ver graduados
$graduados = $sistema->obtenerGraduados();
echo "Total de graduados: " . count($graduados) . "\n";
```

---

## 📁 Estructura del Proyecto

```
TALLER_5/
│
├── proyecto_final.php       # Archivo principal con el sistema completo
├── README.md               # Este archivo
│
└── [Estructura de clases]
    ├── Clase Estudiante
    │   ├── Gestión de datos personales
    │   ├── Gestión de materias y calificaciones
    │   ├── Cálculo de promedios
    │   └── Sistema de flags automático
    │
    └── Clase SistemaGestionEstudiantes
        ├── Gestión de estudiantes
        ├── Búsqueda y filtrado
        ├── Generación de reportes
        └── Estadísticas avanzadas
```

---

## 🔧 Características Técnicas

### Programación Orientada a Objetos
- Encapsulación completa con atributos privados
- Métodos públicos bien definidos
- Type hinting en todos los parámetros
- Uso de getters para acceso controlado

### Funciones de Orden Superior
El sistema implementa tres funciones de orden superior de PHP:

1. **array_reduce**: Para cálculos agregados (promedios, mejor estudiante)
2. **array_filter**: Para filtrado de estudiantes por criterios
3. **array_map**: Para transformación de datos en reportes

### Validación y Manejo de Errores
- Validación de calificaciones (0-100)
- Retorno de `null` para búsquedas sin resultados
- Excepciones para datos inválidos
- Verificación de existencia antes de operaciones

### Arreglos Multidimensionales
- Arreglos asociativos para materias: `['materia' => calificacion]`
- Arreglos multidimensionales para reportes
- Estructuras anidadas para estadísticas por carrera

---

## 📊 Salida del Sistema

Al ejecutar `proyecto_final.php`, verás:

1. ✅ Creación de 10 estudiantes de prueba
2. 📋 Listado completo con detalles
3. 📈 Promedio general del sistema
4. 🏆 Mejor estudiante
5. 🎓 Filtrado por carrera
6. 🥇 Ranking completo
7. 📊 Reporte de rendimiento por materia
8. 🔍 Ejemplos de búsqueda
9. 📉 Estadísticas por carrera
10. 🏅 Estudiantes por flags
11. 🎓 Demostración de graduación
12. ❌ Manejo de errores

---

## 🎓 Casos de Uso

### Institución Educativa
- Gestionar base de datos de estudiantes
- Generar reportes de rendimiento
- Identificar estudiantes en riesgo académico
- Reconocer estudiantes destacados

### Departamento Académico
- Analizar rendimiento por materia
- Comparar rendimiento entre carreras
- Generar rankings académicos
- Gestionar procesos de graduación

### Docentes
- Consultar información de estudiantes
- Identificar necesidades de apoyo académico
- Generar reportes de rendimiento

---

## ⚠️ Notas Importantes

### Validaciones
- Las calificaciones deben estar entre 0 y 100
- Los IDs de estudiantes deben ser únicos
- Las búsquedas retornan arreglos vacíos si no hay resultados

### Flags Automáticos
Los flags se actualizan automáticamente cada vez que se agrega una materia:
- **Honor Roll**: Se asigna si el promedio es ≥ 90
- **Riesgo Académico**: Se asigna si el promedio es < 60
- **Materias Reprobadas**: Se asigna si hay al menos una calificación < 60
- **Excelencia**: Se asigna si todas las materias son ≥ 85

### Graduación
- Un estudiante graduado se mueve del arreglo activo al de graduados
- Los graduados no aparecen en listados ni reportes de estudiantes activos
- La operación es irreversible en la sesión actual

---

## 🤝 Contribuciones

Este proyecto fue desarrollado como parte del **TALLER_5** del curso de Programación en PHP.

### Conceptos Aplicados
- ✅ Arreglos multidimensionales y asociativos
- ✅ Programación Orientada a Objetos
- ✅ Funciones de orden superior
- ✅ Type hinting
- ✅ Manejo de errores
- ✅ Métodos mágicos (`__toString`)

---

## 📞 Soporte

Para preguntas sobre el uso del sistema:
1. Revisa los ejemplos de código en este README
2. Consulta los comentarios en `proyecto_final.php`
3. Ejecuta el archivo para ver la demostración completa

---

## 📝 Licencia

Este proyecto es de uso educativo para el TALLER_5.

---

**Desarrollado con ❤️ para el aprendizaje de PHP avanzado**

*Versión 1.0 - Sistema de Gestión de Estudiantes*