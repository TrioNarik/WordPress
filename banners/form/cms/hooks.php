<?php

$HE_HOOKS = [];

// ========================
// Rejestruje HOOK 
// ========================
/**
 * @param string   $hook_name Nazwa hooka
 * @param callable $callback  Funkcja, która zostanie wywołana przy aktywacji hooka
 * @param int      $priority  Im mniejsza liczba, tym wcześniej funkcja zostanie wywołana (domyślnie 10)
 */
function add_hook($hook_name, $callback, $priority = 10) {
    global $HE_HOOKS;
    if (!isset($HE_HOOKS[$hook_name])) {
        $HE_HOOKS[$hook_name] = [];
    }
    $HE_HOOKS[$hook_name][] = ['callback' => $callback, 'priority' => $priority];
}



// ========================
// Wywołuje HOOK
// ========================
/**
 * Wywołuje wszystkie funkcje przypisane do danego hooka, posortowane według priorytetu
 *
 * @param string $hook_name Nazwa hooka
 * @param mixed  ...$args   Argumenty przekazywane do funkcji callback
 */
function do_hook($hook_name, ...$args) {
    global $HE_HOOKS;
    if (!empty($HE_HOOKS[$hook_name])) {
        // Sortowanie po priorytecie rosnąco (niższy priorytet = wcześniej)
        usort($HE_HOOKS[$hook_name], function($a, $b) {
            return $a['priority'] <=> $b['priority'];
        });

        foreach ($HE_HOOKS[$hook_name] as $hook) {
            call_user_func_array($hook['callback'], $args);
        }
    }
}




// ========================
// Usuwa HOOK
// ========================
function remove_hook($hook_name, $callback) {
    global $HE_HOOKS;
    if (!empty($HE_HOOKS[$hook_name])) {
        foreach ($HE_HOOKS[$hook_name] as $key => $registered_callback) {
            if ($registered_callback === $callback) {
                unset($HE_HOOKS[$hook_name][$key]);
            }
        }
    }
}

// ========================
// Sprawdza czy hook istnieje
// ========================
function hook_exists($hook_name) {
    global $HE_HOOKS;
    return !empty($HE_HOOKS[$hook_name]);
}


// ========================
// Pobiera wszystkie hooki
// ========================
function get_hooks() {
    global $HE_HOOKS;
    return $HE_HOOKS;
}

// ========================
// Pobiera wszystkie hooki o danej nazwie
// ========================
function get_hooks_by_name($hook_name) {
    global $HE_HOOKS;
    return !empty($HE_HOOKS[$hook_name]) ? $HE_HOOKS[$hook_name] : [];
}
