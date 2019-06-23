import './../scss/app.scss';
import WebSocket from "./Connection/WebSocket";
import Loader from "./Interface/Components/Loader";
import Router from "./Navigation/Router";
import LogIn from "./Controller/LogIn";
import Handler from "./Navigation/Handler";
import Register from "./Controller/Register";
import History from "./Navigation/History";
import Dashboard from "./Controller/Dashboard";
import Sidebar from "./Interface/Components/Navigation/Sidebar";
import Read from "./Controller/Read";

const history = new History();
const router  = new Router(history);

const connection = new WebSocket();

const handler = new Handler(router, connection);

const sidebar = new Sidebar(handler);

const logInController = new LogIn(handler, router);

router.add('/', logInController);
router.add('/login', logInController);
router.add('/register', new Register(handler, router));
router.add('/dashboard', new Dashboard(sidebar, handler, connection));
router.add('/read/([0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12})', new Read(), false);

connection.connect(() => {
    Loader.hide();

    router.run('/');
});
