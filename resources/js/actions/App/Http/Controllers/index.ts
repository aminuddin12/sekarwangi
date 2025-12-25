import System from './System'
import Settings from './Settings'
import Auth from './Auth'
import Api from './Api'
import Super from './Super'

const Controllers = {
    System: Object.assign(System, System),
    Settings: Object.assign(Settings, Settings),
    Auth: Object.assign(Auth, Auth),
    Api: Object.assign(Api, Api),
    Super: Object.assign(Super, Super),
}

export default Controllers