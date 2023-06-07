import { ref } from 'vue'
import axios from 'axios'
import printJS from 'print-js'

export default function usePrintDocument() {
    const printing = ref(false)

    const printDocument = async (type, id) => {
        try {
            printing.value = true
            const response = await axios({
                method: 'get',
                url: route('print-document', {_query: {type: type, id: id}})
            })
            printJS({ printable: response.data, type: 'pdf', base64: true })
        } catch (e) {
            console.error('could not print document')
        } finally {
            printing.value = false
        }
    }

    return { printing, printDocument }
}
