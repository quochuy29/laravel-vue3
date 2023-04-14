class Login {
    /**
     * CheckLogin start
     * @memberof checkLogin
     */
    start() {
        const body = document.querySelector("body");
        const modalButton = document.querySelector(".modal-button");
        const closeButton = document.querySelector(".close-button");
        const scrollDown = document.querySelector(".scroll-down");
        let isOpened = false;
  
        if (!body) {
            return;
        }
    
        window.addEventListener("scroll", () => {
            if (window.scrollY > window.innerHeight / 3 && !isOpened) {
        
            isOpened = true;
            scrollDown.style.display = "none";
              this.openModal();
            }
        });

        if (modalButton !== null && closeButton !== null) {
            modalButton.addEventListener("click", this.openModal);
            closeButton.addEventListener("click", this.closeModal);
            
            // document.onkeydown = evt => {
            //   evt = evt || window.event;
            //   evt.keyCode === 27 ? this.closeModal() : false;
            // };
          }
    }
    openModal() {
        const modal = document.querySelector(".modal");
        const body = document.querySelector("body");
        modal.classList.add("is-open");
        body.style.overflow = "hidden";
    }
    closeModal() {
        const modal = document.querySelector(".modal");
        const body = document.querySelector("body");
        modal.classList.remove("is-open");
        body.style.overflow = "initial";
    }
  }
  
  const init = () => {
    const login = new Login();
    login.start();
  };
  
  /**
   * login
   * @type {{init: init}}
   */
  const login = {
    init
  };
  
  export default login;  