import login from "../plugins/login";

function load() {
    window.removeEventListener('loaded', load);
    login.init();
}

export default load;
  