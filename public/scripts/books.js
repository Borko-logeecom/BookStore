import {getData, postData, deleteData, putData, getQueryParameter} from './ajax.js'

document.addEventListener('DOMContentLoaded', () => {
    const appContainer = document.getElementById('app-container')

    const backButtonContainer = document.createElement('div')
    backButtonContainer.className = 'back-button-container'

    const backButton = document.createElement('a')
    backButton.className = 'button back-button'
    backButton.href = '../index.php'

    const backImg = document.createElement('img')
    backImg.src = '../images/arrow.png'
    backImg.alt = 'back'
    backImg.className = 'back-image'

    backButton.appendChild(backImg)
    backButtonContainer.appendChild(backButton)
    appContainer.appendChild(backButtonContainer)

    const container = document.createElement('div')
    container.className = 'container'
    appContainer.appendChild(container)

    const addButtonContainer = document.createElement('div')
    addButtonContainer.className = 'add-container'

    const addButton = document.createElement('a')
    addButton.className = 'add'
    addButton.href = '#'

    const addImg = document.createElement('img')
    addImg.src = '../images/add.png'
    addImg.alt = 'add'
    addImg.className = 'add-image'

    addButton.appendChild(addImg)
    addButtonContainer.appendChild(addButton)
    appContainer.appendChild(addButtonContainer)

    const authorId = getQueryParameter('id')

    function loadBooks() {
        getData(`/api/books?authorId=${authorId}`)
            .then(books => renderBooks(books))
            .catch(err => {
                console.error('Error loading books:', err)
                window.location.replace(`error.phtml?message=${encodeURIComponent(err.message)}`)
            })
    }

    function renderBooks(books) {
        while (container.firstChild) {
            container.removeChild(container.firstChild)
        }

        if (books.length === 0) {
            container.textContent = 'No books found for this author.'

            return
        }

        const table = document.createElement('table')
        const thead = document.createElement('thead')
        const headerRow = document.createElement('tr')

        const thBook = document.createElement('th')
        thBook.className = 'book-col'
        thBook.textContent = 'Book'
        headerRow.appendChild(thBook)

        const thActions = document.createElement('th')
        thActions.className = 'actions-col'
        thActions.textContent = 'Actions'
        headerRow.appendChild(thActions)

        thead.appendChild(headerRow)
        table.appendChild(thead)

        const tbody = document.createElement('tbody')

        books.forEach(book => {
            const tr = document.createElement('tr')

            const tdTitle = document.createElement('td')
            const b = document.createElement('b')
            b.textContent = `${book.title} (${book.publish_year})`
            tdTitle.appendChild(b)

            const tdActions = document.createElement('td')
            tdActions.className = 'actions-col'

            const actionDiv = document.createElement('div')
            actionDiv.className = 'actions'

            const editButton = document.createElement('a')
            editButton.className = 'button edit'
            editButton.href = '#'

            const editImg = document.createElement('img')
            editImg.src = '../images/pen.png'
            editImg.alt = 'pen'

            editButton.appendChild(editImg)
            editButton.onclick = () => showEditForm(book)

            const deleteButton = document.createElement('a')
            deleteButton.className = 'button delete'
            deleteButton.href = '#'

            const deleteImg = document.createElement('img')
            deleteImg.src = '../images/minus.png'
            deleteImg.alt = 'minus'

            deleteButton.appendChild(deleteImg)
            deleteButton.onclick = () => showDeleteDialog(book)

            actionDiv.appendChild(editButton)
            actionDiv.appendChild(deleteButton)
            tdActions.appendChild(actionDiv)

            tr.appendChild(tdTitle)
            tr.appendChild(tdActions)
            tbody.appendChild(tr)
        })

        table.appendChild(tbody)
        container.appendChild(table)
    }

    function showEditForm(book) {
        removeExistingForm()

        const formWrapper = document.createElement('div')
        formWrapper.className = 'form-wrapper'

        const closeButton = document.createElement('button')
        closeButton.type = 'button'
        closeButton.className = 'close-form'
        closeButton.textContent = 'X'
        formWrapper.appendChild(closeButton)

        const form = document.createElement('form')
        const fieldset = document.createElement('fieldset')
        const legend = document.createElement('legend')
        legend.textContent = `Book Edit (${book.id})`
        fieldset.appendChild(legend)

        const titleLabel = document.createElement('label')
        titleLabel.setAttribute('for', 'title')
        titleLabel.textContent = 'Title'
        fieldset.appendChild(titleLabel)

        const titleInput = document.createElement('input')
        titleInput.type = 'text'
        titleInput.id = 'title'
        titleInput.name = 'title'
        titleInput.value = book.title
        fieldset.appendChild(titleInput)

        const yearLabel = document.createElement('label')
        yearLabel.setAttribute('for', 'year')
        yearLabel.textContent = 'Year'
        fieldset.appendChild(yearLabel)

        const yearInput = document.createElement('input')
        yearInput.type = 'number'
        yearInput.id = 'year'
        yearInput.name = 'year'
        yearInput.value = book.year
        fieldset.appendChild(yearInput)

        const buttonDiv = document.createElement('div')
        buttonDiv.className = 'button-div'
        const saveButton = document.createElement('button')
        saveButton.type = 'submit'
        saveButton.textContent = 'Save'
        buttonDiv.appendChild(saveButton)
        fieldset.appendChild(buttonDiv)

        form.appendChild(fieldset)
        formWrapper.appendChild(form)

        closeButton.addEventListener('click', () => {
            document.body.classList.remove('overlay-active')
            formWrapper.remove()
        })

        form.onsubmit = (e) => {
            e.preventDefault()

            const updatedBook = {
                id: book.id,
                author_id: authorId,
                title: titleInput.value,
                year: yearInput.value
            }

            postData(`/api/books/${book.id}/edit`, updatedBook)
                .then(() => {
                    document.body.classList.remove('overlay-active')
                    formWrapper.remove()
                    loadBooks()
                })
                .catch(error => {
                    console.error('Error updating book:', error)
                    window.location.href = `error.phtml?message=${encodeURIComponent(error.message)}`
                })
        }

        document.body.classList.add('overlay-active')
        appContainer.appendChild(formWrapper)
    }

    function showAddForm() {
        removeExistingForm()

        const formWrapper = document.createElement('div')
        formWrapper.className = 'form-wrapper'

        const closeButton = document.createElement('button')
        closeButton.type = 'button'
        closeButton.className = 'close-form'
        closeButton.textContent = 'X'
        formWrapper.appendChild(closeButton)

        const form = document.createElement('form')
        const fieldset = document.createElement('fieldset')
        const legend = document.createElement('legend')
        legend.textContent = 'Add Book'
        fieldset.appendChild(legend)

        const titleLabel = document.createElement('label')
        titleLabel.setAttribute('for', 'title')
        titleLabel.textContent = 'Title'
        fieldset.appendChild(titleLabel)

        const titleInput = document.createElement('input')
        titleInput.type = 'text'
        titleInput.id = 'title'
        titleInput.name = 'title'
        titleInput.value = ''
        fieldset.appendChild(titleInput)

        const yearLabel = document.createElement('label')
        yearLabel.setAttribute('for', 'year')
        yearLabel.textContent = 'Year'
        fieldset.appendChild(yearLabel)

        const yearInput = document.createElement('input')
        yearInput.type = 'number'
        yearInput.id = 'year'
        yearInput.name = 'year'
        yearInput.value = ''
        fieldset.appendChild(yearInput)

        const buttonDiv = document.createElement('div')
        buttonDiv.className = 'button-div'
        const saveButton = document.createElement('button')
        saveButton.type = 'submit'
        saveButton.textContent = 'Save'
        buttonDiv.appendChild(saveButton)
        fieldset.appendChild(buttonDiv)

        form.appendChild(fieldset)
        formWrapper.appendChild(form)


        closeButton.addEventListener('click', () => {
            document.body.classList.remove('overlay-active')
            formWrapper.remove()
        })

        form.onsubmit = (e) => {
            e.preventDefault()

            const newBook = {
                author_id: authorId,
                title: titleInput.value,
                year: yearInput.value
            }

            postData(`/api/books/create`, newBook)
                .then(() => {
                    document.body.classList.remove('overlay-active')
                    formWrapper.remove()
                    loadBooks()
                })
                .catch(error => {
                    console.error('Error creating book:', error)
                    window.location.href = `error.phtml?message=${encodeURIComponent(error.message)}`
                })
        }

        document.body.classList.add('overlay-active')
        appContainer.appendChild(formWrapper)
    }

    function showDeleteDialog(book) {
        removeExistingModal()

        const dialog = document.createElement('div')
        dialog.className = 'book-delete-dialog'

        const modalContent = document.createElement('div')
        modalContent.className = 'modal-content'

        const modalHeader = document.createElement('div')
        modalHeader.className = 'modal-header'

        const exclamationIcon = document.createElement('span')
        exclamationIcon.className = 'exclamation-icon'
        exclamationIcon.textContent = '❗'
        modalHeader.appendChild(exclamationIcon)

        const h2 = document.createElement('h2')
        h2.textContent = 'Delete Book'
        modalHeader.appendChild(h2)
        modalContent.appendChild(modalHeader)

        const modalBody = document.createElement('div')
        modalBody.className = 'modal-body'
        const p = document.createElement('p')
        p.textContent = `
            You are about to delete the book ${book.title}.
            If you proceed with this action, the application will permanently delete this book from the database.
        `
        modalBody.appendChild(p)
        modalContent.appendChild(modalBody)

        const form = document.createElement('form')
        const fieldset = document.createElement('fieldset')
        fieldset.className = 'modal-footer'

        const cancelButton = document.createElement('button')
        cancelButton.id = 'cancelButton'
        cancelButton.name = 'action'
        cancelButton.value = 'cancel'
        cancelButton.type = 'button'
        cancelButton.textContent = 'Cancel'
        fieldset.appendChild(cancelButton)

        const deleteButton = document.createElement('button')
        deleteButton.id = 'deleteButton'
        deleteButton.name = 'action'
        deleteButton.value = 'delete'
        deleteButton.type = 'submit'
        deleteButton.textContent = 'Delete'
        fieldset.appendChild(deleteButton)

        form.appendChild(fieldset)
        modalContent.appendChild(form)
        dialog.appendChild(modalContent)

        cancelButton.onclick = () => {
            document.body.classList.remove('overlay-active')
            dialog.remove()
        }
        form.onsubmit = (e) => {
            e.preventDefault()

            postData(`/api/books/${book.id}/delete`)
                .then(() => {
                    document.body.classList.remove('overlay-active')
                    dialog.remove()
                    loadBooks()
                })
                .catch(error => console.error('Error deleting book:', error))
        }

        document.body.classList.add('overlay-active')
        appContainer.appendChild(dialog)
    }

    function removeExistingForm() {
        const existingForm = document.querySelector('.form-wrapper')
        if (existingForm) existingForm.remove()

        const deleteDialog = document.querySelector('.book-delete-dialog')
        if (deleteDialog) deleteDialog.remove()
    }

    function removeExistingModal() {
        const existingModal = document.querySelector('.modal')
        if (existingModal) existingModal.remove()
    }

    addButton.onclick = (e) => {
        e.preventDefault()
        showAddForm()
    }

    loadBooks()
})