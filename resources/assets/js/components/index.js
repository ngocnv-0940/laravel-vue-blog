import Vue from 'vue'
import Icon from './Icon'
import Card from './Card'
import Child from './Child'
import Button from './Button'
import Checkbox from './Checkbox'
import { AlertError, AlertSuccess } from 'vform'
import HasError from './HasError'

Vue.component(Icon.name, Icon)
Vue.component(Card.name, Card)
Vue.component(Child.name, Child)
Vue.component(Button.name, Button)
Vue.component(Checkbox.name, Checkbox)
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.component(AlertSuccess.name, AlertSuccess)
