import {workflow} from "./workflow";
import {xhr} from "./xhr";
export class admin {
  constructor() {
    this.userList();
    this.btndelete();
    this.btnRestore();
    this.sortable();
    this.workflow = new workflow();
    this.xhr = new xhr();
  }

  sortable() { $("#sortable").sortable({stop : this.positionChange}); }

  positionChange(event, ui) {
    $("#sortable").find("input").each(function(index) { $(this).val(index); });
  }
  btndelete() {
    $(document).on("click", ".OperationLinkDelete",
                   this.btndeleteOnClick.bind(this));
  }

  btndeleteOnClick(event) {
    event.preventDefault();
    $(".BtnDeleteModalConfirm")
        .attr("href", $(event.currentTarget).attr("href"));
    $(".BtnDeleteModalConfirm")
        .attr("data-id", $(event.currentTarget).attr("data-id"));
    $(".BtnDeleteModalConfirm").off("click");
    $(".BtnDeleteModalConfirm").on("click", this.confirmDelete.bind(this));
    $("#deleteModal").modal();
  }

  btnRestore() {
    $(document).on("click", ".OperationLinkRestore",
                   this.btnRestoreOnclick.bind(this));
  }

  btnRestoreOnclick(event) {
    event.preventDefault();
    $("#restoreModal").modal();
  }

  confirmDelete(event) {
    event.preventDefault();
    let data = [];
    let url = $(".BtnDeleteModalConfirm").attr("href");
    let id = $(".BtnDeleteModalConfirm").attr("data-id");

    data.push(id);
    this.xhr.delete(url, data);
  }

  userList() { window.rolesFormatter = this.rolesFormatter; }

  rolesFormatter(roles, row) {
    let ul = document.createElement("ul");

    $(roles).each(function(value) {
      let li = document.createElement("li");

      li.innerHTML = roles[value];

      ul.append(li);
    });

    return ul.outerHTML;
  }
}
