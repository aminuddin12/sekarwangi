import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../wayfinder'
/**
* @see \App\Http\Controllers\Public\HomeController::home
* @see app/Http/Controllers/Public/HomeController.php:12
* @route '/'
*/
export const home = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: home.url(options),
    method: 'get',
})

home.definition = {
    methods: ["get","head"],
    url: '/',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Public\HomeController::home
* @see app/Http/Controllers/Public/HomeController.php:12
* @route '/'
*/
home.url = (options?: RouteQueryOptions) => {
    return home.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Public\HomeController::home
* @see app/Http/Controllers/Public/HomeController.php:12
* @route '/'
*/
home.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: home.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Public\HomeController::home
* @see app/Http/Controllers/Public/HomeController.php:12
* @route '/'
*/
home.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: home.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Public\HomeController::home
* @see app/Http/Controllers/Public/HomeController.php:12
* @route '/'
*/
const homeForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: home.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Public\HomeController::home
* @see app/Http/Controllers/Public/HomeController.php:12
* @route '/'
*/
homeForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: home.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Public\HomeController::home
* @see app/Http/Controllers/Public/HomeController.php:12
* @route '/'
*/
homeForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: home.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

home.form = homeForm

/**
* @see \App\Http\Controllers\Public\OrganizationController::about
* @see app/Http/Controllers/Public/OrganizationController.php:10
* @route '/about'
*/
export const about = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: about.url(options),
    method: 'get',
})

about.definition = {
    methods: ["get","head"],
    url: '/about',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Public\OrganizationController::about
* @see app/Http/Controllers/Public/OrganizationController.php:10
* @route '/about'
*/
about.url = (options?: RouteQueryOptions) => {
    return about.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Public\OrganizationController::about
* @see app/Http/Controllers/Public/OrganizationController.php:10
* @route '/about'
*/
about.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: about.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Public\OrganizationController::about
* @see app/Http/Controllers/Public/OrganizationController.php:10
* @route '/about'
*/
about.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: about.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Public\OrganizationController::about
* @see app/Http/Controllers/Public/OrganizationController.php:10
* @route '/about'
*/
const aboutForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: about.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Public\OrganizationController::about
* @see app/Http/Controllers/Public/OrganizationController.php:10
* @route '/about'
*/
aboutForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: about.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Public\OrganizationController::about
* @see app/Http/Controllers/Public/OrganizationController.php:10
* @route '/about'
*/
aboutForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: about.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

about.form = aboutForm

/**
* @see \App\Http\Controllers\Public\OrganizationController::visionMission
* @see app/Http/Controllers/Public/OrganizationController.php:15
* @route '/vision-mission'
*/
export const visionMission = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: visionMission.url(options),
    method: 'get',
})

visionMission.definition = {
    methods: ["get","head"],
    url: '/vision-mission',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Public\OrganizationController::visionMission
* @see app/Http/Controllers/Public/OrganizationController.php:15
* @route '/vision-mission'
*/
visionMission.url = (options?: RouteQueryOptions) => {
    return visionMission.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Public\OrganizationController::visionMission
* @see app/Http/Controllers/Public/OrganizationController.php:15
* @route '/vision-mission'
*/
visionMission.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: visionMission.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Public\OrganizationController::visionMission
* @see app/Http/Controllers/Public/OrganizationController.php:15
* @route '/vision-mission'
*/
visionMission.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: visionMission.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Public\OrganizationController::visionMission
* @see app/Http/Controllers/Public/OrganizationController.php:15
* @route '/vision-mission'
*/
const visionMissionForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: visionMission.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Public\OrganizationController::visionMission
* @see app/Http/Controllers/Public/OrganizationController.php:15
* @route '/vision-mission'
*/
visionMissionForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: visionMission.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Public\OrganizationController::visionMission
* @see app/Http/Controllers/Public/OrganizationController.php:15
* @route '/vision-mission'
*/
visionMissionForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: visionMission.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

visionMission.form = visionMissionForm

/**
* @see \App\Http\Controllers\Public\OrganizationController::organization
* @see app/Http/Controllers/Public/OrganizationController.php:20
* @route '/organization'
*/
export const organization = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: organization.url(options),
    method: 'get',
})

organization.definition = {
    methods: ["get","head"],
    url: '/organization',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Public\OrganizationController::organization
* @see app/Http/Controllers/Public/OrganizationController.php:20
* @route '/organization'
*/
organization.url = (options?: RouteQueryOptions) => {
    return organization.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Public\OrganizationController::organization
* @see app/Http/Controllers/Public/OrganizationController.php:20
* @route '/organization'
*/
organization.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: organization.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Public\OrganizationController::organization
* @see app/Http/Controllers/Public/OrganizationController.php:20
* @route '/organization'
*/
organization.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: organization.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Public\OrganizationController::organization
* @see app/Http/Controllers/Public/OrganizationController.php:20
* @route '/organization'
*/
const organizationForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: organization.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Public\OrganizationController::organization
* @see app/Http/Controllers/Public/OrganizationController.php:20
* @route '/organization'
*/
organizationForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: organization.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Public\OrganizationController::organization
* @see app/Http/Controllers/Public/OrganizationController.php:20
* @route '/organization'
*/
organizationForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: organization.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

organization.form = organizationForm

/**
* @see \App\Http\Controllers\Public\OrganizationController::legality
* @see app/Http/Controllers/Public/OrganizationController.php:25
* @route '/legality'
*/
export const legality = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: legality.url(options),
    method: 'get',
})

legality.definition = {
    methods: ["get","head"],
    url: '/legality',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Public\OrganizationController::legality
* @see app/Http/Controllers/Public/OrganizationController.php:25
* @route '/legality'
*/
legality.url = (options?: RouteQueryOptions) => {
    return legality.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Public\OrganizationController::legality
* @see app/Http/Controllers/Public/OrganizationController.php:25
* @route '/legality'
*/
legality.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: legality.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Public\OrganizationController::legality
* @see app/Http/Controllers/Public/OrganizationController.php:25
* @route '/legality'
*/
legality.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: legality.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Public\OrganizationController::legality
* @see app/Http/Controllers/Public/OrganizationController.php:25
* @route '/legality'
*/
const legalityForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: legality.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Public\OrganizationController::legality
* @see app/Http/Controllers/Public/OrganizationController.php:25
* @route '/legality'
*/
legalityForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: legality.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Public\OrganizationController::legality
* @see app/Http/Controllers/Public/OrganizationController.php:25
* @route '/legality'
*/
legalityForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: legality.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

legality.form = legalityForm

/**
* @see \App\Http\Controllers\Auth\LoginController::login
* @see app/Http/Controllers/Auth/LoginController.php:20
* @route '/login'
*/
export const login = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: login.url(options),
    method: 'get',
})

login.definition = {
    methods: ["get","head"],
    url: '/login',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\LoginController::login
* @see app/Http/Controllers/Auth/LoginController.php:20
* @route '/login'
*/
login.url = (options?: RouteQueryOptions) => {
    return login.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\LoginController::login
* @see app/Http/Controllers/Auth/LoginController.php:20
* @route '/login'
*/
login.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: login.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\LoginController::login
* @see app/Http/Controllers/Auth/LoginController.php:20
* @route '/login'
*/
login.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: login.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Auth\LoginController::login
* @see app/Http/Controllers/Auth/LoginController.php:20
* @route '/login'
*/
const loginForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: login.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\LoginController::login
* @see app/Http/Controllers/Auth/LoginController.php:20
* @route '/login'
*/
loginForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: login.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\LoginController::login
* @see app/Http/Controllers/Auth/LoginController.php:20
* @route '/login'
*/
loginForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: login.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

login.form = loginForm

/**
* @see \App\Http\Controllers\Auth\RegisterController::register
* @see app/Http/Controllers/Auth/RegisterController.php:20
* @route '/register'
*/
export const register = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})

register.definition = {
    methods: ["get","head"],
    url: '/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\RegisterController::register
* @see app/Http/Controllers/Auth/RegisterController.php:20
* @route '/register'
*/
register.url = (options?: RouteQueryOptions) => {
    return register.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\RegisterController::register
* @see app/Http/Controllers/Auth/RegisterController.php:20
* @route '/register'
*/
register.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\RegisterController::register
* @see app/Http/Controllers/Auth/RegisterController.php:20
* @route '/register'
*/
register.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: register.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Auth\RegisterController::register
* @see app/Http/Controllers/Auth/RegisterController.php:20
* @route '/register'
*/
const registerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: register.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\RegisterController::register
* @see app/Http/Controllers/Auth/RegisterController.php:20
* @route '/register'
*/
registerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: register.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\RegisterController::register
* @see app/Http/Controllers/Auth/RegisterController.php:20
* @route '/register'
*/
registerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: register.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

register.form = registerForm

/**
* @see \App\Http\Controllers\Auth\LoginController::logout
* @see app/Http/Controllers/Auth/LoginController.php:155
* @route '/logout'
*/
export const logout = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: logout.url(options),
    method: 'post',
})

logout.definition = {
    methods: ["post"],
    url: '/logout',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\LoginController::logout
* @see app/Http/Controllers/Auth/LoginController.php:155
* @route '/logout'
*/
logout.url = (options?: RouteQueryOptions) => {
    return logout.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\LoginController::logout
* @see app/Http/Controllers/Auth/LoginController.php:155
* @route '/logout'
*/
logout.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: logout.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Auth\LoginController::logout
* @see app/Http/Controllers/Auth/LoginController.php:155
* @route '/logout'
*/
const logoutForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: logout.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Auth\LoginController::logout
* @see app/Http/Controllers/Auth/LoginController.php:155
* @route '/logout'
*/
logoutForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: logout.url(options),
    method: 'post',
})

logout.form = logoutForm
