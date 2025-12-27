import Public from './Public'
import System from './System'
import Settings from './Settings'
import Auth from './Auth'
import Api from './Api'
import Super from './Super'

const Controllers = {
    Public: Object.assign(Public, Public),
    System: Object.assign(System, System),
    Settings: Object.assign(Settings, Settings),
    Auth: Object.assign(Auth, Auth),
    Api: Object.assign(Api, Api),
    Super: Object.assign(Super, Super),
}

export default Controllers