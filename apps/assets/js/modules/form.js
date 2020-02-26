import "whatwg-fetch";
import { xhr } from "./xhr";
export class form {
  constructor() {
    this.xhr = new xhr();
    this.add();
    this.delete();
    this.session();
    this.save();
    this.btndelete();
    this.btnEmpty();
    this.minimize();
  }

  minimize() {
    $(".ToggleFieldset").on("click", function() {
      let $maximize = $(this).find(".fa-window-maximize");
      let $minimize = $(this).find(".fa-window-minimize");
      let $table = $(this)
        .closest("fieldset")
        .find("table");
      let $row = $(this)
        .closest("fieldset")
        .find(".row");

      if ($table.length == 0) {
        $row.toggle("blind");
      } else {
        $table.toggle("blind");
      }
      if ($minimize.hasClass("d-none")) {
        $minimize.removeClass("d-none");
        $maximize.addClass("d-none");
      }
      if ($maximize.hasClass("d-none")) {
        $maximize.removeClass("d-none");
        $minimize.addClass("d-none");
      }
    });
  }

  confirmEmpty(event) {
    event.preventDefault();
    let data = [];
    let url = $(".BtnemptyModalConfirm").attr("href");
    this.xhr.empty(url);
  }

  btnEmptyOnClick(event) {
    event.preventDefault();
    $(".BtnemptyModalConfirm").attr(
      "href",
      $(event.currentTarget).attr("href")
    );
    $(".BtnemptyModalConfirm").off("click");
    $(".BtnemptyModalConfirm").on("click", this.confirmEmpty.bind(this));
    $("#emptyModal").modal();
  }

  btnEmpty() {
    $(".BtnActionEmpty").on("click", this.btnEmptyOnClick.bind(this));
  }

  confirmDelete(event) {
    event.preventDefault();
    let data = [];
    let url = $(".BtnDeleteModalConfirm").attr("href");

    if ($("main").find("form").length == 1) {
      let id = $("main")
        .find("form")
        .attr("data-id");

      data.push(id);
    } else if ($("#CrudList").length == 1) {
      let json = $("#CrudList").bootstrapTable("getSelections");

      $(json).each(function(index, row) {
        data.push(row.id);
      });
    }

    this.xhr.delete(url, data);
  }

  btndeleteOnClick(event) {
    event.preventDefault();
    $(".BtnDeleteModalConfirm").attr(
      "href",
      $(event.currentTarget).attr("href")
    );
    $(".BtnDeleteModalConfirm").off("click");
    $(".BtnDeleteModalConfirm").on("click", this.confirmDelete.bind(this));
    if ($("main").find("form").length == 1) {
      $("#deleteModal").modal();
    } else if ($("#CrudList").length != 0) {
      let json = $("#CrudList").bootstrapTable("getSelections");

      if (json.length != 0) {
        $("#deleteModal").modal();
      }
    }
  }

  btndelete() {
    $(".BtnActionDelete").on("click", this.btndeleteOnClick.bind(this));
  }

  paramDelete(object) {
    let parameters = [];

    for (var property in object) {
      if (object.hasOwnProperty(property)) {
        parameters.push(encodeURI("id[" + property + "]=" + object[property]));
      }
    }

    return parameters.join("&");
  }

  save() {
    $(".BtnActionSave").on("click", function(event) {
      $(event.currentTarget).attr("disable", true);
      event.preventDefault();
      $("main")
        .find("form")
        .trigger("submit");
    });
  }

  session() {
    $("input, select, textarea").each(function(index) {
      let $form = $(this).closest("form");
      let $formName = $($form).attr("name");
      let $inputID = $(this).attr("id");

      if ($formName != undefined) {
        console.log($formName + " " + $inputID);
      }
    });
  }
  add() {
    let collectionAdd = document.querySelectorAll(".BtnCollectionAdd");

    if (collectionAdd.length == 0) {
      return;
    }

    $(document).on("click", ".BtnCollectionAdd", function(event) {
      let fieldset = $(event.currentTarget).closest("fieldset");
      let prototype = $(fieldset).attr("data-prototype");
      let index = $(fieldset).find("tr").length;
      let tags = prototype.replace(/__name__/g, index);
      let tabCollection = $(fieldset).find(".TabCollection");

      $(tabCollection).append(tags);
    });
  }
  delete() {
    $(document).on("click", ".BtnCollectionDelete", function() {
      $(this)
        .closest(".CollectionRow")
        .remove();
    });
  }
}
