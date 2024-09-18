import { createStore } from "vuex"
import theme from "./theme"

const store = createStore({
    modules: {
        theme
    }
})

export default store