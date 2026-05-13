<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { router, Link } from '@inertiajs/vue3';

/**
 * =============================================================================
 * DEMOSTRACIÓN DEL SISTEMA DE EVENTOS DE INERTIA.JS
 * =============================================================================
 *
 * Inertia proporciona un sistema de eventos que te permite "engancharte" a los
 * diversos momentos del ciclo de vida de la biblioteca.
 *
 * Esta página demuestra todos los eventos disponibles con ejemplos prácticos.
 *
 * Métodos para registrar listeners:
 * 1. router.on('evento', callback) - API oficial de Inertia
 * 2. document.addEventListener('inertia:evento', callback) - Eventos nativos del navegador
 *
 * =============================================================================
 */

// =============================================================================
// PROPIEDADES RECIBIDAS DEL CONTROLADOR
// =============================================================================

defineProps({
    message: {
        type: String,
        default: '',
    },
    loadedAt: {
        type: String,
        default: '',
    },
});

// =============================================================================
// ESTADO REACTIVO PARA MOSTRAR LOS EVENTOS
// =============================================================================

// Array para almacenar el historial de eventos ocurridos
const eventLog = ref([]);

// Estado para el indicador de carga
const isLoading = ref(false);

// Estado para la barra de progreso de uploads
const uploadProgress = ref(0);

// =============================================================================
// FUNCIONES AUXILIARES
// =============================================================================

/**
 * Agrega un evento al log visual
 * @param {string} eventName - Nombre del evento
 * @param {string} description - Descripción de lo ocurrido
 * @param {object} detail - Datos adicionales del evento
 */
function logEvent(eventName, description, detail = {}) {
    const timestamp = new Date().toLocaleTimeString();

    // Agregar al inicio del array (más reciente primero)
    eventLog.value.unshift({
        id: Date.now(),
        timestamp,
        event: eventName,
        description,
        detail,
    });

    // Mantener solo los últimos 10 eventos en el log visual
    if (eventLog.value.length > 10) {
        eventLog.value.pop();
    }
}

// =============================================================================
// REGISTRO DE LISTENERS DE EVENTOS DE INERTIA
// =============================================================================

/**
 * -----------------------------------------------------------------------------
 * EVENTO: 'before' (Antes)
 * -----------------------------------------------------------------------------
 * Se dispara justo ANTES de que se realice una petición al servidor.
 *
 * Propósito principal: Permite INTERCEPTAR visitas y decidir si proceder.
 *
 * Útil para:
 * - Confirmar navegación con el usuario (ej: "¿Estás seguro de salir?")
 * - Validar datos antes de navegar
 * - Guardar cambios no guardados
 *
 * CARACTERÍSTICA IMPORTANTE: Este evento ES CANCELABLE.
 * Puedes prevenir la visita retornando false o llamando event.preventDefault()
 *
 * @param {object} event - Objeto del evento con detalles de la visita
 * -----------------------------------------------------------------------------
 */
router.on('before', (event) => {
    logEvent('before', 'Evento cancelable - se puede impedir la navegación', {
        url: event.detail.visit.url,
        method: event.detail.visit.method,
    });

    // Ejemplo: Confirmar antes de navegar a páginas sensibles
    // return confirm('¿Estás seguro de navegar a ' + event.detail.visit.url + '?')
});

/**
 * -----------------------------------------------------------------------------
 * EVENTO: 'start' (Inicio)
 * -----------------------------------------------------------------------------
 * Se dispara cuando la petición al servidor ha COMENZADO.
 *
 * A diferencia de 'before', este evento NO es cancelable - la petición ya comenzó.
 *
 * Útil para:
 * - Mostrar indicadores de carga (spinners, skeletons)
 * - Iniciar contadores de tiempo
 * - Deshabilitar botones
 *
 * @param {object} event - Objeto con detalles de la visita iniciada
 * -----------------------------------------------------------------------------
 */
router.on('start', (event) => {
    isLoading.value = true;
    logEvent('start', 'La petición ha comenzado (no cancelable)', {
        url: event.detail.visit.url,
    });
});

/**
 * -----------------------------------------------------------------------------
 * EVENTO: 'progress' (Progreso)
 * -----------------------------------------------------------------------------
 * Se dispara durante la SUBIDA DE ARCHIVOS (file uploads).
 *
 * Proporciona información sobre el progreso de la carga:
 * - event.detail.progress.percentage: Porcentaje completado (0-100)
 * - event.detail.progress.loaded: Bytes cargados
 * - event.detail.progress.total: Total de bytes
 *
 * Útil para:
 * - Mostrar barra de progreso de upload
 * - Actualizar porcentaje visual
 * - Calcular tiempo restante estimado
 *
 * NOTA: Este evento solo se dispara durante uploads con FormData.
 * @param {object} event - Objeto con información de progreso
 * -----------------------------------------------------------------------------
 */
router.on('progress', (event) => {
    uploadProgress.value = event.detail.progress.percentage;
    logEvent(
        'progress',
        'Progreso de upload: ' + event.detail.progress.percentage + '%',
        {
            loaded: event.detail.progress.loaded,
            total: event.detail.progress.total,
        },
    );
});

/**
 * -----------------------------------------------------------------------------
 * EVENTO: 'finish' (Finalización)
 * -----------------------------------------------------------------------------
 * Se dispara cuando la petición ha TERMINADO (ya sea éxito o error).
 *
 * Este evento siempre se dispara, sin importar si la respuesta fue exitosa
 * o si hubo un error HTTP.
 *
 * Útil para:
 * - Ocultar indicadores de carga
 * - Reiniciar estados de formularios
 * - Mostrar notificaciones finales
 *
 * @param {object} event - Objeto de la visita completada
 * -----------------------------------------------------------------------------
 */
router.on('finish', (event) => {
    isLoading.value = false;
    uploadProgress.value = 0;
    logEvent('finish', 'La petición ha terminado', {
        url: event.detail.visit.url,
    });
});

/**
 * -----------------------------------------------------------------------------
 * EVENTO: 'success' (Éxito)
 * -----------------------------------------------------------------------------
 * Se dispara cuando la respuesta del servidor fue EXITOSA (código 2xx).
 *
 * Útil para:
 * - Mostrar mensajes de éxito
 * - Actualizar datos en memoria
 * - Redireccionar después de acción completada
 * - Guardar datos en caché
 *
 * @param {object} event - Objeto con la respuesta exitosa
 * -----------------------------------------------------------------------------
 */
router.on('success', (event) => {
    logEvent('success', 'Respuesta exitosa del servidor', {
        page: event.detail.page.component,
        status: event.detail.page.props,
    });
});

/**
 * -----------------------------------------------------------------------------
 * EVENTO: 'error' (Error)
 * -----------------------------------------------------------------------------
 * Se dispara cuando la respuesta del servidor fue un ERROR (código 4xx/5xx).
 *
 * Útil para:
 * - Mostrar mensajes de error al usuario
 * - Loguear errores para debugging
 * - Mostrar validaciones de formulario
 *
 * NOTA: Los errores de validación (422) también disparan este evento.
 *
 * @param {object} event - Objeto con los errores recibidos
 * -----------------------------------------------------------------------------
 */
router.on('error', (event) => {
    logEvent('error', 'Error en la respuesta del servidor', {
        errors: event.detail.errors,
    });
});

/**
 * -----------------------------------------------------------------------------
 * EVENTO: 'invalid' -> 'httpException' (Excepción HTTP)
 * -----------------------------------------------------------------------------
 * Este evento ha sido RENOMBRADO en Inertia v3 de 'invalid' a 'httpException'.
 *
 * Se dispara cuando la respuesta del servidor no es una página de Inertia válida.
 *
 * Esto puede ocurrir cuando:
 * - El servidor devuelve un error de validación (422)
 * - Se lanza una excepción PHP
 * - La respuesta no sigue el protocolo Inertia
 *
 * Útil para:
 * - Manejar errores de validación
 * - Mostrar errores de servidor
 *
 * @param {object} event - Objeto con los detalles de la excepción
 * -----------------------------------------------------------------------------
 */
router.on('httpException', (event) => {
    logEvent(
        'httpException',
        'Respuesta no válida de Inertia (ej: error 422)',
        {
            status: event.detail.status,
            message: event.detail.message,
        },
    );
});

/**
 * -----------------------------------------------------------------------------
 * EVENTO: 'exception' -> 'networkError' (Error de Red)
 * -----------------------------------------------------------------------------
 * Este evento ha sido RENOMBRADO en Inertia v3 de 'exception' a 'networkError'.
 *
 * Se dispara cuando hay un error de RED o conexión:
 * - Pérdida de conexión a internet
 * - Timeout de la petición
 * - Error de DNS
 * - Conexión rechazada por el servidor
 *
 * Útil para:
 * - Mostrar mensaje de "Sin conexión"
 * - Reintentar automáticamente la petición
 * - Notificar al usuario del problema
 *
 * @param {object} event - Objeto con el error de red
 * -----------------------------------------------------------------------------
 */
router.on('networkError', (event) => {
    logEvent('networkError', 'Error de red/conexión', {
        message: event.detail.error?.message || 'Error de conexión',
    });
});

// =============================================================================
// MÉTODO ALTERNATIVO: Usando eventos nativos del navegador
// =============================================================================

/**
 * También puedes escuchar los eventos de Inertia usando la API nativa
 * de eventos del navegador. Solo prepende "inertia:" al nombre del evento.
 *
 * Esto es útil si necesitas agregar listeners desde fuera del componente
 * Vue o en código que no tiene acceso directo al router de Inertia.
 *
 * Ejemplo:
 * document.addEventListener('inertia:start', (event) => { ... })
 * document.removeEventListener('inertia:finish', (event) => { ... })
 */

// =============================================================================
// EJEMPLO: Cómo prevenir una navegación (con interceptación)
// =============================================================================

/**
 * Ejemplo de función que demostraría la cancelación de navegación.
 * En la práctica, se usaría dentro del evento 'before' retornando false.
 *
 * function confirmNavigation(url) {
 *     return confirm('¿Seguir a ' + url + '?')
 * }
 */

// =============================================================================
// EJEMPLO: Uso del router para navegación programática
// =============================================================================

/**
 * Además de los eventos, el router permite navegación programática:
 *
 * router.visit(url, options)
 *
 * Opciones disponibles:
 * - method: 'get' | 'post' | 'put' | 'patch' | 'delete'
 * - data: objeto con datos a enviar
 * - replace: true para reemplazar la historia (no agregar nuevo entry)
 * - preserveState: true para mantener el estado del componente
 * - preserveScroll: true para mantener la posición del scroll
 * - only: array de props a incluir (optimización)
 * - except: array de props a excluir (optimización)
 * - headers: headers HTTP adicionales
 * - onStart, onFinish, onSuccess, onError: callbacks de eventos
 */

/**
 * Navegación programática de ejemplo
 */
function navigateSlow() {
    router.visit('/event-demo/slow', {
        preserveScroll: true,
        onSuccess: () => {
            console.log('Navegación lenta completada');
        },
    });
}

/**
 * Ejemplo de navegación que Disparará error
 */
function navigateError() {
    router.visit('/event-demo/error');
}

// =============================================================================
// CICLO DE VIDA: Cleanup al desmontar el componente
// =============================================================================

/**
 * Es IMPORTANTE limpiar los listeners cuando el componente se desmonta
 * para evitar memory leaks y comportamientos inesperados.
 *
 * Router.on() retorna una función de cleanup que puedes llamar.
 *
 * Alternativamente, puedes usar router.off() para remover listeners específicos.
 */

// Variables para almacenar las funciones de cleanup
let cleanupBefore, cleanupStart, cleanupFinish, cleanupSuccess, cleanupError;

onMounted(() => {
    // Los listeners ya están registrados arriba con router.on()
    // Aquí podrías guardar referencias si necesitaras removerlos manualmente

    console.log('🚀 Demo de eventos de Inertia iniciada');
    console.log(
        'ℹ️ Intenta navegar entre páginas para ver los eventos en acción',
    );
});

onUnmounted(() => {
    // Cleanup de todos los listeners
    // En Inertia v3, los listeners se limpian automáticamente al desmontar,
    // pero es buena práctica saber cómo hacerlo manualmente si fuera necesario

    console.log('🧹 Componente desmontado, listeners limpiados');
});

// =============================================================================
// MANERA ALTERNATIVA: Usar la API de eventos nativa del navegador
// =============================================================================

/**
 * Si prefieres usar la API nativa de eventos en lugar del router de Inertia,
 * aquí hay un ejemplo de cómo hacerlo:
 *
 * // En onMounted:
 * document.addEventListener('inertia:start', handleStart)
 * document.addEventListener('inertia:finish', handleFinish)
 *
 * // En onUnmounted:
 * document.removeEventListener('inertia:start', handleStart)
 * document.removeEventListener('inertia:finish', handleFinish)
 *
 * El objeto event.detail contiene la misma información que en router.on()
 */
</script>

<template>
    <div class="min-h-screen bg-gray-50 p-8">
        <div class="mx-auto max-w-4xl">
            <!-- Encabezado -->
            <div class="mb-8">
                <h1 class="mb-2 text-3xl font-bold text-gray-800">
                    Sistema de Eventos de Inertia.js
                </h1>
                <p class="text-gray-600">
                    Esta página demuestra todos los eventos disponibles en
                    Inertia. Abre la consola del navegador para ver los logs
                    detallados.
                </p>
            </div>

            <!-- Indicador de carga global -->
            <div
                v-if="isLoading"
                class="mb-4 rounded-lg border border-blue-300 bg-blue-100 p-4"
            >
                <div class="flex items-center gap-2">
                    <div
                        class="h-5 w-5 animate-spin rounded-full border-2 border-blue-600 border-t-transparent"
                    ></div>
                    <span class="text-blue-700">Cargando página...</span>
                </div>
            </div>

            <!-- Barra de progreso de upload -->
            <div v-if="uploadProgress > 0" class="mb-4">
                <div class="mb-1 text-sm text-gray-600">
                    Progreso de upload: {{ uploadProgress }}%
                </div>
                <div class="h-2 w-full rounded-full bg-gray-200">
                    <div
                        class="h-2 rounded-full bg-green-500 transition-all duration-300"
                        :style="{ width: uploadProgress + '%' }"
                    ></div>
                </div>
            </div>

            <!-- Botones de demostración -->
            <div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-lg font-semibold">
                        Navegación Normal
                    </h2>
                    <p class="mb-4 text-sm text-gray-600">
                        Usa los enlaces normales de Inertia para navegar.
                        Observa cómo se disparan los eventos.
                    </p>
                    <div class="flex gap-2">
                        <Link
                            href="/event-demo"
                            class="rounded bg-gray-200 px-4 py-2 hover:bg-gray-300"
                        >
                            Recargar esta página
                        </Link>
                        <Link
                            href="/"
                            class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600"
                        >
                            Ir al Home
                        </Link>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-lg font-semibold">
                        Navegación Programática
                    </h2>
                    <p class="mb-4 text-sm text-gray-600">
                        Usa el router de Inertia para navegar desde JavaScript.
                    </p>
                    <div class="flex gap-2">
                        <button
                            @click="navigateSlow"
                            class="rounded bg-yellow-500 px-4 py-2 text-white hover:bg-yellow-600"
                        >
                            Navegación lenta (2s)
                        </button>
                        <button
                            @click="navigateError"
                            class="rounded bg-red-500 px-4 py-2 text-white hover:bg-red-600"
                        >
                            Simular error 500
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mensaje de la navegación lenta -->
            <div
                v-if="message"
                class="mb-8 rounded-lg border border-green-300 bg-green-100 p-4"
            >
                <h3 class="font-semibold text-green-800">¡Éxito!</h3>
                <p class="text-green-700">{{ message }}</p>
                <p class="mt-1 text-sm text-green-600">
                    Cargado a las: {{ loadedAt }}
                </p>
            </div>

            <!-- Log de eventos -->
            <div class="rounded-lg bg-white p-6 shadow">
                <h2 class="mb-4 text-lg font-semibold">Historial de Eventos</h2>
                <p class="mb-4 text-sm text-gray-600">
                    Aquí se muestran los eventos que se han disparado durante la
                    navegación.
                </p>

                <div
                    v-if="eventLog.length === 0"
                    class="py-8 text-center text-gray-400"
                >
                    No hay eventos registrados aún. ¡Navega entre páginas para
                    ver los eventos!
                </div>

                <div v-else class="space-y-2">
                    <div
                        v-for="log in eventLog"
                        :key="log.id"
                        class="rounded border border-gray-200 bg-gray-50 p-3 text-sm"
                    >
                        <div class="mb-1 flex items-center gap-2">
                            <span class="font-mono text-xs text-gray-500">{{
                                log.timestamp
                            }}</span>
                            <span
                                class="rounded px-2 py-0.5 text-xs font-bold"
                                :class="{
                                    'bg-yellow-100 text-yellow-800':
                                        log.event === 'before',
                                    'bg-blue-100 text-blue-800':
                                        log.event === 'start',
                                    'bg-green-100 text-green-800':
                                        log.event === 'success',
                                    'bg-red-100 text-red-800':
                                        log.event === 'error',
                                    'bg-purple-100 text-purple-800':
                                        log.event === 'finish',
                                    'bg-orange-100 text-orange-800':
                                        log.event === 'progress',
                                    'bg-indigo-100 text-indigo-800':
                                        log.event === 'httpException',
                                    'bg-pink-100 text-pink-800':
                                        log.event === 'networkError',
                                }"
                            >
                                {{ log.event }}
                            </span>
                        </div>
                        <div class="text-gray-700">{{ log.description }}</div>
                        <pre
                            v-if="log.detail && Object.keys(log.detail).length"
                            class="mt-1 overflow-x-auto rounded bg-gray-100 p-2 text-xs text-gray-500"
                            >{{ JSON.stringify(log.detail, null, 2) }}
                        </pre>
                    </div>
                </div>
            </div>

            <!-- Referencia de eventos -->
            <div class="mt-8 rounded-lg bg-white p-6 shadow">
                <h2 class="mb-4 text-lg font-semibold">
                    Referencia de Eventos
                </h2>
                <div class="grid grid-cols-1 gap-4 text-sm md:grid-cols-2">
                    <div>
                        <h3 class="mb-2 font-medium text-gray-700">
                            Eventos de Ciclo de Vida
                        </h3>
                        <ul class="space-y-1 text-gray-600">
                            <li>
                                <code class="rounded bg-gray-100 px-1"
                                    >before</code
                                >
                                - Antes de la petición (cancelable)
                            </li>
                            <li>
                                <code class="rounded bg-gray-100 px-1"
                                    >start</code
                                >
                                - Petición iniciada
                            </li>
                            <li>
                                <code class="rounded bg-gray-100 px-1"
                                    >progress</code
                                >
                                - Progreso de upload
                            </li>
                            <li>
                                <code class="rounded bg-gray-100 px-1"
                                    >finish</code
                                >
                                - Petición terminada
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="mb-2 font-medium text-gray-700">
                            Eventos de Respuesta
                        </h3>
                        <ul class="space-y-1 text-gray-600">
                            <li>
                                <code class="rounded bg-gray-100 px-1"
                                    >success</code
                                >
                                - Respuesta exitosa (2xx)
                            </li>
                            <li>
                                <code class="rounded bg-gray-100 px-1"
                                    >error</code
                                >
                                - Error HTTP (4xx/5xx)
                            </li>
                            <li>
                                <code class="rounded bg-gray-100 px-1"
                                    >httpException</code
                                >
                                - Excepción HTTP (antes 'invalid')
                            </li>
                            <li>
                                <code class="rounded bg-gray-100 px-1"
                                    >networkError</code
                                >
                                - Error de red (antes 'exception')
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
